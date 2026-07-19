@extends('layouts.app')

@section('title', 'Быстрая игра в шахматы')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8f1e5; }
        .quick-play { padding: 20px; max-width: 1400px; margin: 0 auto; background: #f8f1e5; min-height: 100vh; }
        .game-container { background: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(139, 69, 19, 0.15); border: 2px solid #8b4513; }
        #board { width: 560px; height: 560px; max-width: 100%; margin: 0 auto; box-shadow: 0 5px 20px rgba(0,0,0,0.2); border-radius: 8px; overflow: hidden; border: 3px solid #8b4513; }
        .white-1e1d7 { background-color: #f0d9b5 !important; }
        .black-3c85d { background-color: #b58863 !important; }
        .highlight-square::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 30%; height: 30%; background: radial-gradient(circle, rgba(76, 175, 80, 0.8) 0%, rgba(76, 175, 80, 0.4) 100%); border-radius: 50%; pointer-events: none; z-index: 10; }
        .selected-square { background: rgba(255, 215, 0, 0.4) !important; box-shadow: inset 0 0 0 3px #8b4513; }
        .capture-square::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 85%; height: 85%; border: 5px solid rgba(220, 20, 60, 0.7); border-radius: 50%; pointer-events: none; z-index: 10; box-sizing: border-box; }
        @media (max-width: 768px) { #board { width: 450px; height: 450px; } }
        @media (max-width: 480px) { #board { width: 350px; height: 350px; } .game-container { padding: 15px; } }
        .controls { text-align: center; margin: 20px 0; padding: 20px; background: #f8f1e5; border-radius: 10px; border: 1px solid #8b4513; }
        .control-btn { margin: 5px; min-width: 150px; padding: 10px 20px; font-size: 16px; border-radius: 8px; transition: all 0.3s; font-weight: 600; border: 2px solid #5d4037; }
        .control-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3); }
        .game-info { background: #f8f1e5; color: #4a2200; border-radius: 10px; padding: 20px; margin-bottom: 20px; border: 2px solid #8b4513; }
        #chat-messages { height: 300px; overflow-y: auto; border: 2px solid #8b4513; padding: 15px; margin-bottom: 15px; background: #fff; border-radius: 10px; font-size: 14px; }
        #chat-messages div { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e0d0b0; }
        #move-history { height: 300px; overflow-y: auto; padding: 15px; background: #fff; border-radius: 10px; border: 2px solid #8b4513; font-family: 'Courier New', monospace; font-size: 15px; font-weight: bold; }
        .move-row { padding: 3px 5px; border-bottom: 1px solid #e0d0b0; }
        .move-row:nth-child(even) { background: #faf5eb; }
        .white-move { color: #2e7d32; font-weight: bold; margin-right: 10px; }
        .black-move { color: #c62828; font-weight: bold; }
        .move-row strong { color: #8b4513; margin-right: 5px; }
        .room-info { background: #f8f1e5; color: #4a2200; padding: 25px; border-radius: 10px; margin-bottom: 25px; text-align: center; border: 2px solid #8b4513; }
        .player-card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; border: 2px solid #8b4513; transition: all 0.3s; }
        .player-card.you { border-color: #2e7d32; background: #e8f5e9; }
        .player-card.opponent { border-color: #c62828; background: #ffebee; }
        .player-card h5 { color: #4a2200; margin-bottom: 15px; font-size: 20px; font-weight: bold; }
        .color-indicator { display: inline-block; width: 24px; height: 24px; border-radius: 50%; margin-right: 10px; vertical-align: middle; border: 2px solid #8b4513; }
        .color-white { background: #fff; }
        .color-black { background: #333; }
        #turn-indicator { font-size: 20px; font-weight: bold; padding: 15px; border-radius: 10px; margin: 20px 0; border: 2px solid #8b4513; background: #f8f1e5; color: #4a2200; }
        .btn-primary { background: #8b4513; border: 2px solid #5d4037; color: #f0e6d2 !important; }
        .btn-primary:hover { background: #6b3100; }
        .btn-success { background: #2e7d32; border: 2px solid #1b5e20; color: #fff !important; }
        .btn-danger { background: #c62828; border: 2px solid #b71c1c; color: #fff !important; }
        .btn-warning { background: #f57c00; border: 2px solid #e65100; color: #fff !important; }
        .btn-info { background: #5d4037; border: 2px solid #3e2723; color: #f0e6d2 !important; }
        .btn-secondary { background: #6b3100; border: 2px solid #4a2200; color: #f0e6d2 !important; }
        .btn-outline-secondary { background: transparent; border: 2px solid #8b4513; color: #8b4513; }
        .btn-outline-secondary:hover { background: #8b4513; color: #f0e6d2; }
        h1 { color: #8b4513; text-align: center; margin-bottom: 30px; font-size: 36px; font-weight: bold; font-family: 'Georgia', serif; text-transform: uppercase; letter-spacing: 2px; }
        h1 i { margin-right: 10px; color: #6b3100; }
        .card { border: 2px solid #8b4513; border-radius: 10px; margin-bottom: 20px; overflow: hidden; }
        .card-header { background: #8b4513; color: #f0e6d2; border-bottom: 2px solid #5d4037; font-weight: bold; padding: 12px 20px; font-family: 'Georgia', serif; }
        .alert { border-radius: 10px; font-weight: 600; }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 2px solid #2e7d32; }
        .alert-primary { background: #e3f2fd; color: #1565c0; border: 2px solid #1565c0; }
        .alert-danger { background: #ffebee; color: #c62828; border: 2px solid #c62828; }
        .alert-warning { background: #fff3e0; color: #e65100; border: 2px solid #e65100; }
        .game-over-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 10000; }
        .modal-content { background: #fff; border: 3px solid #8b4513; border-radius: 15px; padding: 40px; max-width: 500px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.5); }
        .modal-title { font-size: 32px; color: #8b4513; margin-bottom: 20px; font-family: 'Georgia', serif; }
        .modal-message { font-size: 24px; color: #4a2200; margin-bottom: 30px; }
        .modal-btn { padding: 12px 25px; font-size: 16px; font-weight: bold; border-radius: 8px; border: 2px solid #5d4037; cursor: pointer; transition: all 0.3s; }
        .modal-btn-primary { background: #8b4513; color: #f0e6d2; }
        .modal-btn-primary:hover { background: #6b3100; }
        .modal-btn-secondary { background: #c62828; color: #fff; }
        .modal-btn-secondary:hover { background: #b71c1c; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8f1e5; }
        ::-webkit-scrollbar-thumb { background: #8b4513; border-radius: 4px; }
        .manual-start-btn { margin-top: 10px; background: #8b4513; border: 2px solid #5d4037; color: #f0e6d2 !important; font-weight: bold; }
        .manual-start-btn:hover { background: #6b3100; }
        .text-muted { color: #6b3100 !important; }
        .modal-buttons { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
    </style>
@endpush

@section('content')
<div class="quick-play">
    <h1>
        <i class="fas fa-chess-queen"></i>
        Шахматы онлайн
        <i class="fas fa-chess-king"></i>
    </h1>

    <div class="game-container">
        <div class="room-info">
            <h4><i class="fas fa-crown"></i> Игровая комната <i class="fas fa-crown"></i></h4>
            <p class="mt-2 mb-0" id="connection-status">
                <i class="fas fa-spinner fa-spin"></i> Подключение к серверу...
            </p>
            <button id="manual-start-btn" class="btn manual-start-btn control-btn" style="display:none;" onclick="window.manualStartGame()">
                <i class="fas fa-play"></i> Начать игру
            </button>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="player-card you">
                    <h5><i class="fas fa-user-circle"></i> Вы</h5>
                    <p><strong>Имя:</strong> {{ $user->username ?? 'Гость' }}</p>
                    <p><strong>Цвет:</strong>
                        <span class="color-indicator" id="your-color-indicator"></span>
                        <span id="your-color">ожидание...</span>
                    </p>
                    <p><strong>Статус:</strong> <span id="your-status" class="badge bg-secondary">Ожидание</span></p>
                </div>

                <div class="player-card opponent">
                    <h5><i class="fas fa-user-friends"></i> Соперник</h5>
                    <p><strong>Имя:</strong> <span id="opponent-name">Ожидание...</span></p>
                    <p><strong>Цвет:</strong>
                        <span class="color-indicator" id="opponent-color-indicator"></span>
                        <span id="opponent-color">ожидание...</span>
                    </p>
                    <p><strong>Статус:</strong> <span id="opponent-status" class="badge bg-secondary">Не подключен</span></p>
                </div>

                <div class="game-info">
                    <h5><i class="fas fa-chess"></i> Управление</h5>
                    <div class="d-grid gap-2">
                        <button id="new-game-btn" class="btn btn-primary control-btn">
                            <i class="fas fa-search"></i> Найти игру
                        </button>
                        <button id="flip-board-btn" class="btn btn-secondary control-btn">
                            <i class="fas fa-sync-alt"></i> Перевернуть доску
                        </button>
                        <button id="resign-btn" class="btn btn-danger control-btn" disabled>
                            <i class="fas fa-flag"></i> Сдаться
                        </button>
                        <button id="offer-draw-btn" class="btn btn-warning control-btn" disabled>
                            <i class="fas fa-handshake"></i> Предложить ничью
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div id="board"></div>
                <div class="text-center mt-3">
                    <div class="alert alert-primary" id="turn-indicator">
                        <i class="fas fa-hourglass-start"></i> Ожидание начала игры...
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-comments"></i> Чат с соперником</h5>
                    </div>
                    <div class="card-body">
                        <div id="chat-messages">
                            <div class="text-muted text-center">Чат с соперником</div>
                        </div>
                        <div class="input-group mt-2">
                            <input type="text" id="chat-input" class="form-control" placeholder="Введите сообщение..." disabled>
                            <button id="send-chat" class="btn btn-primary" disabled>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history"></i> История ходов</h5>
                    </div>
                    <div class="card-body">
                        <div id="move-history">
                            <div>Игра еще не началась...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="controls mt-4">
            <p class="text-muted mb-3">
                <i class="fas fa-info-circle"></i> Чтобы начать игру, нажмите "Найти игру"
            </p>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-home"></i> На главную
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.3/chess.js"></script>
<script src="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.js"></script>

<script>
    const USER_ID = {{ $user->id ?? 0 }};
    const USERNAME = '{{ $user->username ?? 'Гость' }}';
    const WS_HOST = window.location.hostname;
    const WS_URL = (WS_HOST === 'localhost' || WS_HOST === '127.0.0.1')
    ? 'ws://localhost:8090'
    : 'wss://' + WS_HOST + '/ws';
    window.WS_URL = WS_URL;

    var game = new Chess();
    var board = null;
    var ws = null;
    var isMyTurn = false;
    var myColor = null;
    var opponent = null;
    var roomId = null;
    var reconnectAttempts = 0;
    var maxReconnectAttempts = 5;
    var gameInProgress = false;
    var selectedSquare = null;
    var possibleMoves = [];
    var gameEnded = false;

    function showGameOverModal(message, result) {
        gameEnded = true;
        $('.game-over-modal').remove();
        var modalHtml = '<div class="game-over-modal"><div class="modal-content"><div class="modal-title"><i class="fas fa-trophy"></i> Игра окончена</div><div class="modal-message">' + message + '</div><div class="modal-buttons"><button class="modal-btn modal-btn-primary" onclick="window.findNewOpponent()"><i class="fas fa-search"></i> Найти нового соперника</button><button class="modal-btn modal-btn-secondary" onclick="window.closeGameOverModal()"><i class="fas fa-times"></i> Закрыть</button></div></div></div>';
        $('body').append(modalHtml);
    }

    window.closeGameOverModal = function() { $('.game-over-modal').remove(); gameEnded = false; };

    window.findNewOpponent = function() {
        $('.game-over-modal').remove(); 
        gameEnded = false;
        if (ws && ws.readyState === WebSocket.OPEN && roomId) {
            ws.send(JSON.stringify({type: 'leave_room', roomId: roomId}));
        }
        resetGame();
        if (ws && ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({type: 'find_new_game'}));
            $('#new-game-btn').prop('disabled', true);
            clearHighlights();
        }
    };

    function initChessBoard() {
        try {
            board = Chessboard('board', {
                position: 'start',
                orientation: 'white',
                draggable: false,
                dropOffBoard: 'snapback',
                onDragStart: function(){ return false; },
                onDrop: function(){ return 'snapback'; },
                onSnapEnd: function() { if (board) board.position(game.fen()); },
                pieceTheme: 'https://chessboardjs.com/img/chesspieces/wikipedia/{piece}.png',
                showErrors: true
            });
            $('#board').on('click', '.square-55d63', function() {
                handleSquareClick($(this).attr('data-square'));
            });
        } catch (error) { 
            console.error('Ошибка:', error); 
        }
    }

    function handleSquareClick(square) {
        if (!gameInProgress || !isMyTurn || gameEnded) return;
        var piece = game.get(square);
        var pieceColor = piece ? piece.color : null;
        var myPieceColor = myColor === 'white' ? 'w' : 'b';
        clearHighlights();
        if (piece && pieceColor === myPieceColor) {
            selectedSquare = square;
            $('.square-55d63[data-square="' + square + '"]').addClass('selected-square');
            var moves = game.moves({ square: square, verbose: true });
            possibleMoves = moves;
            moves.forEach(function(move) {
                var targetSquare = move.to;
                var targetPiece = game.get(targetSquare);
                if (targetPiece) {
                    $('.square-55d63[data-square="' + targetSquare + '"]').addClass('capture-square');
                } else {
                    $('.square-55d63[data-square="' + targetSquare + '"]').addClass('highlight-square');
                }
            });
        } else if (selectedSquare) {
            var targetSquare = square;
            var validMove = possibleMoves.find(function(move) { return move.to === targetSquare; });
            if (validMove) { 
                executeMove(selectedSquare, targetSquare); 
            }
            selectedSquare = null;
            possibleMoves = [];
            clearHighlights();
        }
    }

    function executeMove(from, to) {
        try {
            var move = game.move({ from: from, to: to, promotion: 'q' });
            if (move === null) {
                console.warn('Недопустимый ход');
                return false;
            }
            board.position(game.fen());
            isMyTurn = false;
            updateTurnIndicator();
            
            if (ws && ws.readyState === WebSocket.OPEN && roomId) {
                var message = {
                    type: 'move',
                    roomId: roomId,
                    move: move.san,
                    fen: game.fen()
                };
                ws.send(JSON.stringify(message));
            }
            addMoveToHistory(move.san);
            checkGameEnd();
            return true;
        } catch (e) {
            console.error('Ошибка executeMove:', e);
            return false;
        }
    }

    function clearHighlights() { 
        $('.square-55d63').removeClass('selected-square highlight-square capture-square'); 
    }

    function connectWebSocket() {
        try {
            ws = new WebSocket(WS_URL);
            ws.onopen = function(event) {
                updateConnectionStatus('Подключено', 'success');
                $('#connection-status').html('<i class="fas fa-check-circle"></i> Подключено');
                ws.send(JSON.stringify({ type: 'register', userId: USER_ID, username: USERNAME }));
            };
            ws.onmessage = function(event) {
                try {
                    var data = JSON.parse(event.data);
                    console.log('Получено от сервера:', data);
                    switch(data.type) {
                        case 'ok':
                            console.log('✅ ' + data.message);
                            break;
                        case 'waiting':
                            handleWaiting(data);
                            break;
                        case 'game_start':
                            handleGameStart(data);
                            break;
                        case 'move':
                            handleOpponentMove(data);
                            break;
                        case 'chat':
                            addChatMessage(data.username, data.message);
                            break;
                        case 'player_left':
                            handlePlayerLeft(data);
                            break;
                        case 'game_over':
                            handleGameOver(data);
                            break;
                        case 'draw_offered':
                            handleDrawOffered(data);
                            break;
                        case 'draw_rejected':
                            alert('Соперник отклонил предложение ничьей');
                            break;
                        default:
                            console.log('Неизвестный тип:', data.type);
                    }
                } catch (e) {
                    console.error('Ошибка обработки сообщения:', e);
                }
            };
            ws.onclose = function(event) {
                updateConnectionStatus('Отключено', 'danger');
                $('#connection-status').html('<i class="fas fa-times-circle"></i> Отключено');
                $('#resign-btn, #offer-draw-btn, #chat-input, #send-chat').prop('disabled', true);
                gameInProgress = false;
                if (reconnectAttempts < maxReconnectAttempts) {
                    reconnectAttempts++;
                    var delay = Math.min(3000 * reconnectAttempts, 15000);
                    setTimeout(connectWebSocket, delay);
                } else {
                    $('#new-game-btn').prop('disabled', false);
                }
            };
            ws.onerror = function(error) { 
                console.error('WebSocket ошибка:', error); 
            };
        } catch (e) { 
            console.error('Ошибка WebSocket:', e); 
        }
    }

    function handleWaiting(data) {
        $('#turn-indicator').html('<i class="fas fa-search"></i> Поиск соперника...').removeClass().addClass('alert alert-info');
        $('#new-game-btn').prop('disabled', true);
        $('#manual-start-btn').hide();
        gameInProgress = false;
        gameEnded = false;
        clearHighlights();
        $('.game-over-modal').remove();
    }

    function handleGameStart(data) {
        myColor = data.yourColor;
        opponent = data.opponent;
        
        if (!data.roomId) {
            roomId = Math.floor(Math.random() * 9000) + 1000;
        } else {
            roomId = data.roomId;
        }
        
        gameInProgress = true;
        gameEnded = false;
        updatePlayerInfo();
        updateOpponentInfo();
        game.reset();
        if (board) {
            board.position('start');
            board.orientation(myColor);
            isMyTurn = (myColor === 'white');
        }
        updateTurnIndicator();
        $('#resign-btn, #offer-draw-btn').prop('disabled', false);
        $('#chat-input, #send-chat').prop('disabled', false);
        $('#new-game-btn').prop('disabled', true);
        $('#manual-start-btn').hide();
        $('#move-history').html('');
        clearHighlights();
        $('.game-over-modal').remove();
        
        if (isMyTurn) {
            $('#turn-indicator').removeClass().addClass('alert alert-success').html('<i class="fas fa-chess-board"></i> <strong>Ваш ход</strong>');
        } else {
            $('#turn-indicator').removeClass().addClass('alert alert-primary').html('<i class="fas fa-hourglass-half"></i> <strong>Ход соперника</strong>');
        }
    }

    function handleOpponentMove(data) {
        if (!gameInProgress || gameEnded) return;
        try {
            var move = game.move(data.move);
            if (move) {
                if (board) board.position(game.fen());
                addMoveToHistory(move.san);
                isMyTurn = true;
                updateTurnIndicator();
                checkGameEnd();
                clearHighlights();
            }
        } catch (e) { 
            console.error('Ошибка хода:', e); 
        }
    }

    function handlePlayerLeft(data) {
        resetGame();
        $('#opponent-name').text('Ожидание...');
        $('#opponent-color').text('ожидание...');
        $('#opponent-color-indicator').removeClass('color-white color-black');
        $('#opponent-status').text('Не подключен').removeClass('bg-success').addClass('bg-secondary');
        $('#resign-btn, #offer-draw-btn, #chat-input, #send-chat').prop('disabled', true);
        $('#new-game-btn').prop('disabled', false);
        opponent = null;
        myColor = null;
        gameInProgress = false;
        gameEnded = false;
        clearHighlights();
        $('.game-over-modal').remove();
        setTimeout(function() { 
            showGameOverModal('Соперник покинул игру', 'player_left'); 
        }, 500);
    }

    function handleGameOver(data) {
        if (gameEnded) return;
        gameInProgress = false;
        gameEnded = true;
        var message = '';
        var eloText = '';
        if (data.elo) {
            var myChange = myColor === 'white' ? data.elo.white?.change : data.elo.black?.change;
            if (myChange !== undefined) {
                var sign = myChange > 0 ? '+' : '';
                eloText = ' (ELO: ' + sign + myChange + ')';
            }
        }
        switch(data.result) {
            case 'white_win': 
                message = myColor === 'white' ? '🏆 Победа!' : '💔 Поражение';
                break;
            case 'black_win': 
                message = myColor === 'black' ? '🏆 Победа!' : '💔 Поражение';
                break;
            case 'draw': 
                message = '🤝 Ничья!';
                break;
        }
        message += eloText;
        showNotification(message);
        isMyTurn = false;
        $('#resign-btn, #offer-draw-btn').prop('disabled', true);
        $('#new-game-btn').prop('disabled', false);
        setTimeout(function() { 
            showGameOverModal(message, data.result); 
        }, 500);
    }

    function handleDrawOffered(data) {
        if (confirm(data.fromUsername + ' предлагает ничью. Принять?')) {
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: 'accept_draw', roomId: roomId }));
            }
        } else {
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ type: 'reject_draw', roomId: roomId }));
            }
        }
    }

    function updateConnectionStatus(text, type) {
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
        $('#connection-status').html('<i class="fas ' + icon + '"></i> ' + text);
    }

    function updatePlayerInfo() {
        if (!myColor) return;
        $('#your-color').text(myColor === 'white' ? 'Белые' : 'Черные');
        $('#your-color-indicator').removeClass('color-white color-black').addClass(myColor === 'white' ? 'color-white' : 'color-black');
        $('#your-status').text('В игре').removeClass('bg-secondary').addClass('bg-success');
    }

    function updateOpponentInfo() {
        if (!opponent) return;
        $('#opponent-name').text(opponent.username);
        if (myColor) {
            $('#opponent-color').text(myColor === 'white' ? 'Черные' : 'Белые');
            $('#opponent-color-indicator').removeClass('color-white color-black').addClass(myColor === 'white' ? 'color-black' : 'color-white');
        }
        $('#opponent-status').text('В игре').removeClass('bg-secondary').addClass('bg-success');
    }

    function updateTurnIndicator() {
        if (!myColor || !game || !gameInProgress) return;
        var turn = game.turn();
        isMyTurn = (turn === 'w' && myColor === 'white') || (turn === 'b' && myColor === 'black');
        var turnColor = turn === 'w' ? 'белые' : 'черные';
        if (isMyTurn) {
            $('#turn-indicator').html('<i class="fas fa-chess-board"></i> <strong>Ваш ход</strong> (' + turnColor + ')').removeClass().addClass('alert alert-success');
        } else {
            $('#turn-indicator').html('<i class="fas fa-hourglass-half"></i> <strong>Ход соперника</strong> (' + turnColor + ')').removeClass().addClass('alert alert-primary');
        }
    }

    function addMoveToHistory(move) {
        if (!move) return;
        var history = game.history();
        var moveNumber = Math.ceil(history.length / 2);
        if (history.length % 2 === 1) {
            $('#move-history').append('<div class="move-row"><strong>' + moveNumber + '.</strong> <span class="white-move">' + move + '</span></div>');
        } else {
            var lastRow = $('#move-history .move-row').last();
            if (lastRow.length) {
                lastRow.append(' <span class="black-move">' + move + '</span>');
            } else {
                $('#move-history').append('<div class="move-row"><strong>' + moveNumber + '.</strong> ... <span class="black-move">' + move + '</span></div>');
            }
        }
        $('#move-history').scrollTop($('#move-history')[0].scrollHeight);
    }

    function addChatMessage(sender, message) {
        if (sender === 'system') return;
        var time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        var senderDisplay = (sender === USERNAME) ? '<strong>Вы</strong>' : '<strong>' + sender + '</strong>';
        $('#chat-messages').append('<div><small>[' + time + ']</small> ' + senderDisplay + ': ' + message + '</div>');
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    }

    function checkGameEnd() {
        if (!gameInProgress || gameEnded) return;
        if (game.in_checkmate()) {
            var winner = game.turn() === 'w' ? 'черные' : 'белые';
            endGame(winner === 'белые' ? 'white_win' : 'black_win', 'Шах и мат!');
        } else if (game.in_draw() || game.in_stalemate() || game.in_threefold_repetition() || game.insufficient_material()) {
            endGame('draw', 'Ничья!');
        }
    }

    function endGame(result, message) {
        if (gameEnded) return;
        gameInProgress = false;
        gameEnded = true;
        showNotification(message);
        if (ws && ws.readyState === WebSocket.OPEN && roomId) {
            ws.send(JSON.stringify({ type: 'game_over', roomId: roomId, result: result }));
        }
        isMyTurn = false;
        $('#resign-btn, #offer-draw-btn').prop('disabled', true);
        $('#new-game-btn').prop('disabled', false);
        clearHighlights();
        setTimeout(function() {
            var displayMessage = message;
            if (result === 'white_win') {
                displayMessage = myColor === 'white' ? '🏆 Вы победили!' : '💔 Вы проиграли...';
            } else if (result === 'black_win') {
                displayMessage = myColor === 'black' ? '🏆 Вы победили!' : '💔 Вы проиграли...';
            }
            showGameOverModal(displayMessage, result);
        }, 500);
    }

    function resetGame() {
        if (game) game.reset();
        if (board) board.position('start');
        myColor = null;
        isMyTurn = false;
        gameInProgress = false;
        gameEnded = false;
        selectedSquare = null;
        possibleMoves = [];
        $('#move-history').html('<div>Игра еще не началась...</div>');
        $('#turn-indicator').html('<i class="fas fa-hourglass-start"></i> Ожидание начала игры...').removeClass().addClass('alert alert-primary');
        $('#your-color').text('ожидание...');
        $('#your-color-indicator').removeClass('color-white color-black');
        $('#your-status').text('Ожидание').removeClass('bg-success').addClass('bg-secondary');
        clearHighlights();
        $('.game-over-modal').remove();
    }

    function showNotification(message) {
        $('#turn-indicator').html('<i class="fas fa-trophy"></i> ' + message).removeClass('alert-primary alert-success alert-danger alert-warning alert-info');
        if (message.includes('Победили') || message.includes('Победа')) {
            $('#turn-indicator').addClass('alert-success');
        } else if (message.includes('Ничья')) {
            $('#turn-indicator').addClass('alert-warning');
        } else if (message.includes('проиграли') || message.includes('Поражение')) {
            $('#turn-indicator').addClass('alert-danger');
        } else {
            $('#turn-indicator').addClass('alert-info');
        }
    }

    window.manualStartGame = function() {
        if (ws && ws.readyState === WebSocket.OPEN && roomId) {
            ws.send(JSON.stringify({ type: 'start_game', roomId: roomId }));
            $('#manual-start-btn').hide();
        } else {
            alert('Нет подключения к серверу или комнате');
        }
    };

    $(document).ready(function() {
        initChessBoard();
        connectWebSocket();
        
        $('#new-game-btn').click(function() {
            if (ws && ws.readyState === WebSocket.OPEN) {
                if (roomId) {
                    ws.send(JSON.stringify({type: 'leave_room', roomId: roomId}));
                    roomId = null;
                }
                resetGame();
                ws.send(JSON.stringify({type: 'find_new_game'}));
                $('#new-game-btn').prop('disabled', true);
                clearHighlights();
                $('.game-over-modal').remove();
            } else {
                alert('Нет подключения к серверу. Попробуйте обновить страницу.');
            }
        });
        
        $('#flip-board-btn').click(function() {
            if (board) {
                var newOrientation = board.orientation() === 'white' ? 'black' : 'white';
                board.orientation(newOrientation);
                clearHighlights();
            }
        });
        
        $('#resign-btn').click(function() {
            if (!gameInProgress || gameEnded) return;
            if (confirm('Вы уверены, что хотите сдаться?')) {
                if (ws && ws.readyState === WebSocket.OPEN && roomId) {
                    ws.send(JSON.stringify({type: 'resign', roomId: roomId}));
                    endGame('resign', 'Вы сдались!');
                }
            }
        });
        
        $('#offer-draw-btn').click(function() {
            if (!gameInProgress || gameEnded) return;
            if (ws && ws.readyState === WebSocket.OPEN && roomId) {
                ws.send(JSON.stringify({type: 'offer_draw', roomId: roomId}));
                alert('Предложение ничьей отправлено сопернику');
            }
        });
        
        $('#send-chat').click(sendChatMessage);
        $('#chat-input').keypress(function(e) { if (e.which == 13) sendChatMessage(); });
        
        function sendChatMessage() {
            var message = $('#chat-input').val().trim();
            if (!message) return;
            if (ws && ws.readyState === WebSocket.OPEN && roomId) {
                ws.send(JSON.stringify({ type: 'chat', roomId: roomId, message: message }));
                $('#chat-input').val('');
            } else {
                alert('Нет подключения к чату');
            }
        }
        
        // Пинг для поддержания соединения
        setInterval(function() { 
            if (ws && ws.readyState === WebSocket.OPEN) { 
                ws.send(JSON.stringify({type: 'ping'})); 
            } 
        }, 30000);
    });
</script>
@endsection