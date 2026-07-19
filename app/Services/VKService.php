<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VKService
{
    protected $token;
    protected $version;

    public function __construct()
    {
        $this->token = env('VK_GROUP_TOKEN');
        $this->version = env('VK_API_VERSION', '5.199');
    }

    /**
     * Отправка сообщения в VK
     */
    public function sendMessage($peerId, $text, $log = true)
    {
        try {
            $response = Http::timeout(10)->get('https://api.vk.com/method/messages.send', [
                'access_token' => $this->token,
                'v' => $this->version,
                'peer_id' => $peerId,
                'message' => $text,
                'random_id' => random_int(0, PHP_INT_MAX),
            ]);

            $result = $response->json();

            if (isset($result['error'])) {
                if ($log) {
                    Log::error('VK API error: ' . json_encode($result['error']));
                }
                return $result;
            }

            return $result;
        } catch (\Exception $e) {
            if ($log) {
                Log::error('VK sendMessage error: ' . $e->getMessage());
            }
            return null;
        }
    }

    /**
     * Проверка токена
     */
    public function testToken()
    {
        try {
            $response = Http::get('https://api.vk.com/method/groups.getById', [
                'access_token' => $this->token,
                'v' => $this->version,
            ]);

            $result = $response->json();

            if (isset($result['error'])) {
                return [
                    'success' => false,
                    'error' => $result['error']['error_msg'] ?? 'Unknown error',
                ];
            }

            return [
                'success' => true,
                'group' => $result['response']['groups'][0]['name'] ?? 'Unknown',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Получение информации о пользователе
     */
    public function getUserInfo($userId)
    {
        try {
            $response = Http::get('https://api.vk.com/method/users.get', [
                'access_token' => $this->token,
                'v' => $this->version,
                'user_ids' => $userId,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('VK getUserInfo error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Получить список доступных турниров для подписки
     */
    public function getAvailableTournaments($peerId)
    {
        $user = User::where('vk_id', $peerId)->first();
        
        if (!$user) {
            return null;
        }

        $subscribedIds = TournamentSubscription::where('user_id', $user->id)
            ->pluck('tournament_id')
            ->toArray();

        $tournaments = Tournament::whereNotIn('id', $subscribedIds)
            ->where('status', '!=', 'Завершен')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return [
            'user' => $user,
            'tournaments' => $tournaments,
            'subscribed' => $subscribedIds,
        ];
    }

    /**
     * Получить список турниров, на которые подписан пользователь
     */
    public function getUserSubscriptions($peerId)
    {
        $user = User::where('vk_id', $peerId)->first();
        
        if (!$user) {
            return null;
        }

        return TournamentSubscription::where('user_id', $user->id)
            ->with('tournament')
            ->get();
    }

    /**
     * Подписаться на турнир
     */
    public function subscribeToTournament($peerId, $tournamentId)
    {
        $user = User::where('vk_id', $peerId)->first();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден. Сначала привяжите email: /reset_email'];
        }

        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            return ['success' => false, 'message' => 'Турнир не найден'];
        }

        $exists = TournamentSubscription::where('user_id', $user->id)
            ->where('tournament_id', $tournamentId)
            ->exists();

        if ($exists) {
            return ['success' => false, 'message' => 'Вы уже подписаны на этот турнир'];
        }

        TournamentSubscription::create([
            'user_id' => $user->id,
            'tournament_id' => $tournamentId,
            'notify_draw' => true,
            'notify_start' => true,
            'notify_result' => true,
        ]);

        return [
            'success' => true,
            'message' => "✅ Вы подписались на турнир: {$tournament->name}",
            'tournament' => $tournament,
        ];
    }

    /**
     * Отписаться от турнира
     */
    public function unsubscribeFromTournament($peerId, $tournamentId)
    {
        $user = User::where('vk_id', $peerId)->first();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден'];
        }

        $subscription = TournamentSubscription::where('user_id', $user->id)
            ->where('tournament_id', $tournamentId)
            ->first();

        if (!$subscription) {
            return ['success' => false, 'message' => 'Вы не подписаны на этот турнир'];
        }

        $tournamentName = $subscription->tournament->name ?? 'турнир';
        $subscription->delete();

        return [
            'success' => true,
            'message' => "🔕 Вы отписались от турнира: {$tournamentName}",
        ];
    }

    /**
     * Отправить уведомление о новом турнире всем пользователям с vk_id
     */
    public function notifyNewTournament($tournamentId)
    {
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            Log::error("Турнир {$tournamentId} не найден");
            return 0;
        }

        $users = User::whereNotNull('vk_id')->get();

        if ($users->isEmpty()) {
            Log::info("Нет пользователей с vk_id для уведомления");
            return 0;
        }

        $message = "🆕 НОВЫЙ ТУРНИР!\n\n";
        $message .= "📌 {$tournament->name}\n";
        $message .= "📍 {$tournament->location}\n";
        $message .= "🎮 Режим: " . ($tournament->gamemode->name ?? 'Не указан') . "\n";
        $message .= "🎯 Уровень: " . ($tournament->level->name ?? 'Не указан') . "\n";
        $message .= "📊 Статус: {$tournament->status}\n\n";
        
        if ($tournament->description) {
            $message .= "📝 " . Str::limit($tournament->description, 200) . "\n\n";
        }
        
        $message .= "🔗 Подробнее: " . route('tournaments.index') . "/" . $tournament->id;
        $message .= "\n\n📌 Зарегистрироваться можно на сайте!";

        $sent = 0;
        foreach ($users as $user) {
            if ($this->sendMessage($user->vk_id, $message)) {
                $sent++;
            }
            sleep(1);
        }

        Log::info("Уведомление о новом турнире отправлено {$sent} пользователям");
        return $sent;
    }

    /**
     * Отправить уведомление о жеребьёвке подписанным пользователям
     */
    public function notifyTournamentDraw($tournamentId, $drawData, $drawUrl = null)
    {
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) {
            Log::error("Турнир {$tournamentId} не найден");
            return 0;
        }

        $subscriptions = TournamentSubscription::where('tournament_id', $tournamentId)
            ->where('notify_draw', true)
            ->with('user')
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info("Нет подписчиков на турнир: {$tournament->name}");
            return 0;
        }

        $message = "♟️ НОВАЯ ЖЕРЕБЬЁВКА!\n\n";
        $message .= "📌 Турнир: {$tournament->name}\n";
        $message .= "📅 Дата: " . now()->format('d.m.Y H:i') . "\n\n";
        
        if (is_array($drawData)) {
            foreach ($drawData as $pair) {
                $player1 = $pair['player1'] ?? $pair[0] ?? 'Игрок 1';
                $player2 = $pair['player2'] ?? $pair[1] ?? 'Игрок 2';
                $message .= "▪️ {$player1} vs {$player2}\n";
            }
        } else {
            $message .= $drawData . "\n";
        }

        if ($drawUrl) {
            $message .= "\n🔗 Подробнее: {$drawUrl}";
        }

        $message .= "\n\n📌 Отписаться: /unsubscribe_tournament {$tournamentId}";

        $sent = 0;
        foreach ($subscriptions as $subscription) {
            if ($subscription->user && $subscription->user->vk_id) {
                if ($this->sendMessage($subscription->user->vk_id, $message)) {
                    $sent++;
                }
                sleep(1);
            }
        }

        Log::info("Уведомление о жеребьёвке отправлено {$sent} подписчикам турнира: {$tournament->name}");
        return $sent;
    }
}