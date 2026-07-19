<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;
use Workerman\Connection\TcpConnection;
use App\Models\User;
use App\Models\Game;
use App\Models\Move;
use App\Models\EloHistory;
use App\Services\EloCalculator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WebSocketServer extends Command
{
    protected $signature = 'websocket:serve';
    protected $description = 'Start WebSocket server for chess game';

    private $rooms = [];
    private $waitingPlayers = [];
    private $roomCounter = 1000;

    public function handle()
    {
        $port = 8090;
        $worker = new Worker("websocket://0.0.0.0:{$port}");
        $worker->count = 1;

        $worker->onConnect = function($connection) {
            $connection->userId = null;
            $connection->username = null;
            $connection->roomId = null;
            $this->info("✅ Новый клиент подключился");
        };

        $worker->onMessage = function($connection, $data) {
            try {
                $msg = json_decode($data, true);
                if (!$msg) return;

                switch($msg['type']) {
                    case 'register':
                        $this->handleRegister($connection, $msg);
                        break;
                    case 'find_new_game':
                        $this->handleFindGame($connection);
                        break;
                    case 'move':
                        $this->handleMove($connection, $msg);
                        break;
                    case 'resign':
                        $this->handleResign($connection);
                        break;
                    case 'game_over':
                        $this->handleGameOver($connection, $msg);
                        break;
                    case 'offer_draw':
                        $this->handleOfferDraw($connection);
                        break;
                    case 'accept_draw':
                        $this->handleAcceptDraw($connection);
                        break;
                    case 'reject_draw':
                        $this->handleRejectDraw($connection);
                        break;
                    case 'leave_room':
                        $this->handleLeaveRoom($connection);
                        break;
                    case 'chat':
                        $this->handleChat($connection, $msg);
                        break;
                    case 'ping':
                        $connection->send(json_encode(['type' => 'pong']));
                        break;
                }
            } catch (\Exception $e) {
                $this->error("❌ Ошибка: " . $e->getMessage());
                $connection->send(json_encode([
                    'type' => 'error',
                    'message' => 'Internal server error'
                ]));
            }
        };

        $worker->onClose = function($connection) {
            $this->handleClose($connection);
        };

        Worker::runAll();
    }

    private function getDbConnection()
    {
        try {
            // Пробуем выполнить простой запрос для проверки соединения
            DB::select('SELECT 1');
        } catch (\Exception $e) {
            // Если соединение потеряно - переподключаемся
            DB::purge();
            DB::reconnect();
            $this->info("🔄 База данных переподключена");
        }
    }

    private function handleRegister($connection, $msg)
    {
        $this->getDbConnection();
        
        $connection->userId = $msg['userId'];
        $connection->username = $msg['username'];
        $connection->send(json_encode(['type' => 'ok', 'message' => 'Регистрация успешна']));
        $this->info("👤 Пользователь зарегистрирован: {$connection->username} (ID: {$connection->userId})");
    }

    private function handleFindGame($connection)
    {
        $this->getDbConnection();
        $this->info("🔍 Игрок {$connection->username} ищет игру");
        
        if (!empty($this->waitingPlayers)) {
            $opponent = null;
            foreach ($this->waitingPlayers as $key => $wp) {
                if ($wp !== $connection) {
                    $opponent = $wp;
                    unset($this->waitingPlayers[$key]);
                    break;
                }
            }

            if ($opponent) {
                $this->roomCounter++;
                $roomId = $this->roomCounter;

                try {
                    $game = new Game();
                    $game->white_user_id = $connection->userId;
                    $game->black_user_id = $opponent->userId;
                    $game->status = 'active';
                    $game->started_at = Carbon::now();
                    $game->tournament_id = null;
                    $game->current_fen = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
                    $game->last_move_at = Carbon::now();
                    $game->save();

                    $this->rooms[$roomId] = [
                        'players' => [$connection, $opponent],
                        'game_id' => $game->id,
                        'game' => [
                            'whitePlayer' => $connection->userId,
                            'blackPlayer' => $opponent->userId,
                            'fen' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1',
                            'moves' => [],
                            'started' => time()
                        ]
                    ];

                    $connection->roomId = $roomId;
                    $opponent->roomId = $roomId;

                    $connection->send(json_encode([
                        'type' => 'game_start',
                        'yourColor' => 'white',
                        'opponent' => ['userId' => $opponent->userId, 'username' => $opponent->username],
                        'roomId' => $roomId
                    ]));

                    $opponent->send(json_encode([
                        'type' => 'game_start',
                        'yourColor' => 'black',
                        'opponent' => ['userId' => $connection->userId, 'username' => $connection->username],
                        'roomId' => $roomId
                    ]));

                    $this->info("🎮 Игра началась в комнате {$roomId}: {$connection->username} (white) vs {$opponent->username} (black)");
                    return;
                } catch (\Exception $e) {
                    $this->error("❌ Ошибка создания игры: " . $e->getMessage());
                    $connection->send(json_encode([
                        'type' => 'error',
                        'message' => 'Ошибка создания игры'
                    ]));
                    return;
                }
            }
        }
        
        $this->waitingPlayers[] = $connection;
        $connection->send(json_encode(['type' => 'waiting', 'message' => 'Поиск соперника...']));
        $this->info("⏳ {$connection->username} добавлен в очередь ожидания");
    }

    private function handleMove($connection, $msg)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $this->getDbConnection();
            
            $room = &$this->rooms[$connection->roomId];
            $room['game']['fen'] = $msg['fen'];
            $room['game']['moves'][] = [
                'move' => $msg['move'],
                'player' => $connection->userId,
                'timestamp' => time()
            ];

            try {
                $game = Game::find($room['game_id']);
                if ($game) {
                    $game->current_fen = $msg['fen'];
                    $game->last_move_at = Carbon::now();
                    $game->save();

                    $moveNumber = count($room['game']['moves']);
                    $move = new Move();
                    $move->game_id = $game->id;
                    $move->user_id = $connection->userId;
                    $move->move_number = $moveNumber;
                    $move->move_san = $msg['move'];
                    $move->move_fen = $msg['fen'];
                    $move->created_at = Carbon::now();
                    $move->save();
                }
            } catch (\Exception $e) {
                $this->error("❌ Ошибка сохранения хода: " . $e->getMessage());
            }

            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode([
                        'type' => 'move',
                        'move' => $msg['move'],
                        'fen' => $msg['fen']
                    ]));
                }
            }
            
            $this->info("♟️ Ход {$msg['move']} от {$connection->username} в комнате {$connection->roomId}");
        }
    }

    private function handleResign($connection)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            $result = ($room['game']['whitePlayer'] == $connection->userId) ? 'black_win' : 'white_win';
            $eloData = $this->calculateAndSaveGame($room, $result);

            foreach ($room['players'] as $player) {
                $playerColor = ($room['game']['whitePlayer'] == $player->userId) ? 'white' : 'black';
                $player->send(json_encode([
                    'type' => 'game_over',
                    'result' => $result,
                    'yourColor' => $playerColor,
                    'reason' => 'resign',
                    'elo' => $eloData
                ]));
                $player->roomId = null;
            }
            unset($this->rooms[$connection->roomId]);
            $this->info("🏳️ {$connection->username} сдался, результат: {$result}");
        }
    }

    private function handleGameOver($connection, $msg)
    {
        if (!$connection->roomId || !isset($this->rooms[$connection->roomId])) {
            return;
        }

        $room = $this->rooms[$connection->roomId];
        $result = $msg['result'];
        $eloData = $this->calculateAndSaveGame($room, $result);

        foreach ($room['players'] as $player) {
            $playerColor = ($room['game']['whitePlayer'] == $player->userId) ? 'white' : 'black';
            $player->send(json_encode([
                'type' => 'game_over',
                'result' => $result,
                'yourColor' => $playerColor,
                'elo' => $eloData
            ]));
            $player->roomId = null;
        }
        unset($this->rooms[$connection->roomId]);
        $this->info("🏁 Игра в комнате {$connection->roomId} завершена, результат: {$result}");
    }

    private function handleOfferDraw($connection)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode([
                        'type' => 'draw_offered',
                        'fromUserId' => $connection->userId,
                        'fromUsername' => $connection->username
                    ]));
                }
            }
            $this->info("🤝 {$connection->username} предложил ничью");
        }
    }

    private function handleAcceptDraw($connection)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            $eloData = $this->calculateAndSaveGame($room, 'draw');

            foreach ($room['players'] as $player) {
                $playerColor = ($room['game']['whitePlayer'] == $player->userId) ? 'white' : 'black';
                $player->send(json_encode([
                    'type' => 'game_over',
                    'result' => 'draw',
                    'yourColor' => $playerColor,
                    'elo' => $eloData
                ]));
                $player->roomId = null;
            }
            unset($this->rooms[$connection->roomId]);
            $this->info("🤝 Ничья принята в комнате {$connection->roomId}");
        }
    }

    private function handleRejectDraw($connection)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode(['type' => 'draw_rejected']));
                }
            }
            $this->info("❌ {$connection->username} отклонил ничью");
        }
    }

    private function handleLeaveRoom($connection)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode(['type' => 'player_left']));
                    $player->roomId = null;
                }
            }
            unset($this->rooms[$connection->roomId]);
            $connection->roomId = null;
            $this->info("👋 {$connection->username} покинул комнату");
        }
    }

    private function handleChat($connection, $msg)
    {
        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode([
                        'type' => 'chat',
                        'message' => $msg['message'],
                        'username' => $connection->username
                    ]));
                }
            }
        }
    }

    private function handleClose($connection)
    {
        foreach ($this->waitingPlayers as $key => $player) {
            if ($player === $connection) {
                unset($this->waitingPlayers[$key]);
                break;
            }
        }

        if ($connection->roomId && isset($this->rooms[$connection->roomId])) {
            $room = $this->rooms[$connection->roomId];
            if (count($room['players']) == 2 && isset($room['game'])) {
                $result = ($room['game']['whitePlayer'] == $connection->userId) ? 'black_win' : 'white_win';
                $this->calculateAndSaveGame($room, $result);
            }

            foreach ($room['players'] as $player) {
                if ($player !== $connection) {
                    $player->send(json_encode(['type' => 'player_left']));
                    $player->roomId = null;
                }
            }
            unset($this->rooms[$connection->roomId]);
        }

        $this->info("❌ Клиент {$connection->username} отключился");
    }

    private function calculateAndSaveGame($room, $result)
    {
        // Принудительно переподключаемся к БД
        $this->getDbConnection();
        
        try {
            $whiteUserId = $room['game']['whitePlayer'];
            $blackUserId = $room['game']['blackPlayer'];
            $gameId = $room['game_id'] ?? null;

            $game = null;
            if ($gameId) {
                $game = Game::find($gameId);
            }

            if (!$game) {
                $game = Game::where('white_user_id', $whiteUserId)
                    ->where('black_user_id', $blackUserId)
                    ->where('status', 'active')
                    ->orderBy('started_at', 'desc')
                    ->first();
            }

            if (!$game) {
                $game = new Game();
                $game->white_user_id = $whiteUserId;
                $game->black_user_id = $blackUserId;
                $game->started_at = Carbon::now();
                $game->tournament_id = null;
            }

            $whiteUser = User::find($whiteUserId);
            $blackUser = User::find($blackUserId);

            if (!$whiteUser || !$blackUser) {
                return ['error' => 'User not found'];
            }

            $whiteElo = $whiteUser->elo ?? 1000;
            $blackElo = $blackUser->elo ?? 1000;

            $ratings = EloCalculator::calculateNewRatings($whiteElo, $blackElo, $result);

            if ($result == 'white_win') {
                $game->status = 'white_win';
                $game->winner_id = $whiteUserId;
            } elseif ($result == 'black_win') {
                $game->status = 'black_win';
                $game->winner_id = $blackUserId;
            } else {
                $game->status = 'draw';
                $game->winner_id = null;
            }

            $game->finished_at = Carbon::now();
            $game->current_fen = $room['game']['fen'] ?? '';
            $game->last_move_at = Carbon::now();
            $game->save();

            $whiteUser->elo = $ratings['white_new_elo'];
            $whiteUser->save();
            
            $blackUser->elo = $ratings['black_new_elo'];
            $blackUser->save();

            $eloHistoryWhite = new EloHistory();
            $eloHistoryWhite->user_id = $whiteUserId;
            $eloHistoryWhite->game_id = $game->id;
            $eloHistoryWhite->elo_before = $whiteElo;
            $eloHistoryWhite->elo_after = $ratings['white_new_elo'];
            $eloHistoryWhite->change = $ratings['white_new_elo'] - $whiteElo;
            $eloHistoryWhite->reason = 'online_game';
            $eloHistoryWhite->created_at = Carbon::now();
            $eloHistoryWhite->save();

            $eloHistoryBlack = new EloHistory();
            $eloHistoryBlack->user_id = $blackUserId;
            $eloHistoryBlack->game_id = $game->id;
            $eloHistoryBlack->elo_before = $blackElo;
            $eloHistoryBlack->elo_after = $ratings['black_new_elo'];
            $eloHistoryBlack->change = $ratings['black_new_elo'] - $blackElo;
            $eloHistoryBlack->reason = 'online_game';
            $eloHistoryBlack->created_at = Carbon::now();
            $eloHistoryBlack->save();

            $existingMoves = Move::where('game_id', $game->id)->count();
            if (!empty($room['game']['moves']) && $existingMoves == 0) {
                foreach ($room['game']['moves'] as $i => $moveData) {
                    $move = new Move();
                    $move->game_id = $game->id;
                    $move->user_id = $moveData['player'];
                    $move->move_number = $i + 1;
                    $move->move_san = $moveData['move'];
                    $move->move_fen = $room['game']['fen'] ?? '';
                    $move->created_at = date('Y-m-d H:i:s', $moveData['timestamp']);
                    $move->save();
                }
            }

            return [
                'white' => [
                    'username' => $whiteUser->username,
                    'before' => $whiteElo,
                    'after' => $ratings['white_new_elo'],
                    'change' => $ratings['white_new_elo'] - $whiteElo
                ],
                'black' => [
                    'username' => $blackUser->username,
                    'before' => $blackElo,
                    'after' => $ratings['black_new_elo'],
                    'change' => $ratings['black_new_elo'] - $blackElo
                ],
                'gameId' => $game->id,
            ];
        } catch (\Exception $e) {
            $this->error("❌ Ошибка при сохранении игры: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}