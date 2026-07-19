<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\VKService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VKPolling extends Command
{
    protected $signature = 'vk:poll';
    protected $description = 'Poll VK for updates (Long Poll)';

    protected $token;
    protected $groupId;
    protected $version;
    protected $server;
    protected $key;
    protected $ts;
    protected $vkService;

    public function handle()
    {
        $this->token = env('VK_GROUP_TOKEN');
        $this->groupId = env('VK_GROUP_ID');
        $this->version = env('VK_API_VERSION', '5.199');
        $this->vkService = new VKService();

        $this->info("🔄 Запуск VK Long Poll...");
        $this->info("📌 Group ID: " . $this->groupId);

        if (empty($this->token) || empty($this->groupId)) {
            $this->error("❌ Не заданы VK_GROUP_TOKEN или VK_GROUP_ID в .env");
            return;
        }

        $this->info("🔍 Проверка токена...");
        $test = $this->vkService->testToken();
        if (!$test['success']) {
            $this->error("❌ Токен невалиден: " . $test['error']);
            return;
        }
        $this->info("✅ Токен валиден, группа: " . $test['group']);

        if (!$this->setupLongPoll()) {
            $this->error("❌ Не удалось настроить Long Poll.");
            return;
        }

        if (!$this->getLongPollServer()) {
            $this->error("❌ Не удалось получить Long Poll сервер.");
            return;
        }

        $this->info("✅ Бот запущен, ожидаем сообщения...");
        $this->info("💡 Отправьте /help в группу для проверки");

        while (true) {
            try {
                $this->pollUpdates();
            } catch (\Exception $e) {
                $this->error('❌ Ошибка: ' . $e->getMessage());
                sleep(5);
                $this->getLongPollServer();
            }
        }
    }

    private function setupLongPoll(): bool
    {
        $this->info("⚙️ Настройка Long Poll...");

        $response = Http::get('https://api.vk.com/method/groups.setLongPollSettings', [
            'access_token' => $this->token,
            'v' => $this->version,
            'group_id' => $this->groupId,
            'enabled' => 1,
            'message_new' => 1,
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            $this->error("❌ Ошибка настройки: " . ($data['error']['error_msg'] ?? 'Unknown'));
            return false;
        }

        $this->info("✅ Long Poll настроен");
        return true;
    }

    private function getLongPollServer(): bool
    {
        $this->info("📡 Получение Long Poll сервера...");

        try {
            $response = Http::timeout(30)->get('https://api.vk.com/method/groups.getLongPollServer', [
                'access_token' => $this->token,
                'v' => $this->version,
                'group_id' => $this->groupId,
            ]);

            $data = $response->json();

            if (isset($data['error'])) {
                $this->error("❌ Ошибка VK API: " . ($data['error']['error_msg'] ?? 'Unknown'));
                return false;
            }

            if (isset($data['response'])) {
                $this->server = $data['response']['server'];
                $this->key = $data['response']['key'];
                $this->ts = $data['response']['ts'];
                $this->info("✅ Long Poll сервер получен");
                return true;
            }

            $this->error("❌ Неожиданный ответ: " . json_encode($data));
            return false;
        } catch (\Exception $e) {
            $this->error('❌ Ошибка запроса: ' . $e->getMessage());
            return false;
        }
    }

    private function pollUpdates(): void
    {
        try {
            $response = Http::timeout(60)->get($this->server, [
                'act' => 'a_check',
                'key' => $this->key,
                'ts' => $this->ts,
                'wait' => 25,
            ]);

            $data = $response->json();

            if (isset($data['failed'])) {
                $this->error("❌ Long Poll failed: " . $data['failed']);
                if (in_array($data['failed'], [1, 2, 3])) {
                    $this->info("🔄 Переполучаем Long Poll сервер...");
                    sleep(2);
                    $this->getLongPollServer();
                }
                return;
            }

            if (isset($data['ts'])) {
                $this->ts = $data['ts'];
            }

            if (isset($data['updates']) && is_array($data['updates'])) {
                if (!empty($data['updates'])) {
                    $this->info("📨 Получено " . count($data['updates']) . " обновлений");
                    
                    foreach ($data['updates'] as $update) {
                        $this->info("📌 Тип события: " . ($update['type'] ?? 'unknown'));
                        $this->handleUpdate($update);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Ошибка Long Poll: ' . $e->getMessage());
            $this->info("🔄 Переполучаем Long Poll сервер через 5 секунд...");
            sleep(5);
            $this->getLongPollServer();
        }
    }

    private function handleUpdate(array $update): void
    {
        if (($update['type'] ?? '') !== 'message_new') {
            $this->info("⏭️ Пропускаем событие: " . ($update['type'] ?? 'unknown'));
            return;
        }

        $message = $update['object']['message'] ?? [];
        if (empty($message)) {
            $this->info("⚠️ Пустое сообщение");
            return;
        }

        $peerId = $message['peer_id'] ?? 0;
        $text = trim($message['text'] ?? '');
        $userId = $message['from_id'] ?? $peerId;

        if (empty($text) || empty($peerId)) {
            $this->info("⚠️ Пустой текст или peer_id");
            return;
        }

        if ($userId < 0) {
            $this->info("⏭️ Игнорируем сообщение от группы");
            return;
        }

        $this->info("📨 Получено от $userId: \"$text\"");

        // /reset_email email@example.com
        if (str_starts_with($text, '/reset_email ')) {
            $email = trim(str_replace('/reset_email', '', $text));
            $this->handleSetEmail($peerId, $email, $userId);
            return;
        }

        // /reset_password
        if ($text === '/reset_password') {
            $this->handleResetPassword($peerId);
            return;
        }

        // Просто email
        if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
            $this->handleSetEmail($peerId, $text, $userId);
            return;
        }

        // Код из 6 цифр
        if (preg_match('/^\d{6}$/', $text)) {
            $this->handleVerifyCode($peerId, $text);
            return;
        }

        // /tournaments
        if ($text === '/tournaments') {
            $this->showTournaments($peerId);
            return;
        }

        // /my_tournaments
        if ($text === '/my_tournaments') {
            $this->showMyTournaments($peerId);
            return;
        }

        // /subscribe_tournament
        if (str_starts_with($text, '/subscribe_tournament ')) {
            $tournamentId = trim(str_replace('/subscribe_tournament', '', $text));
            $this->subscribeToTournament($peerId, $tournamentId);
            return;
        }

        // /unsubscribe_tournament
        if (str_starts_with($text, '/unsubscribe_tournament ')) {
            $tournamentId = trim(str_replace('/unsubscribe_tournament', '', $text));
            $this->unsubscribeFromTournament($peerId, $tournamentId);
            return;
        }

        // /start, /help
        if (in_array($text, ['/start', '/help'])) {
            $this->sendMessage($peerId,
                "♟️ ШАХМАТНЫЙ БОТ\n\n" .
                "📌 ВОССТАНОВЛЕНИЕ ПАРОЛЯ:\n" .
                "/reset_email email@example.com – привязать email\n" .
                "/reset_password – восстановить пароль\n\n" .
                "📌 ПОДПИСКИ НА ТУРНИРЫ:\n" .
                "/tournaments – список турниров для подписки\n" .
                "/my_tournaments – мои подписки\n" .
                "/subscribe_tournament [ID] – подписаться на турнир\n" .
                "/unsubscribe_tournament [ID] – отписаться от турнира\n\n" .
                "📌 ПОМОЩЬ:\n" .
                "/help – это меню"
            );
            return;
        }

        $this->sendMessage($peerId,
            "❌ Неизвестная команда\n\n" .
            "Отправьте /help для списка команд"
        );
    }

    private function handleSetEmail($peerId, $email, $userId): void
    {
        $this->info("📧 Привязка email: " . $email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendMessage($peerId, "❌ Некорректный email");
            return;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->sendMessage($peerId, "❌ Пользователь с email {$email} не найден");
            return;
        }

        $user->vk_id = $userId;
        $user->save();

        $this->sendMessage($peerId,
            "✅ Email {$email} привязан!\n\n" .
            "Теперь используйте:\n" .
            "/reset_password – восстановить пароль"
        );
    }

    private function handleResetPassword($peerId): void
    {
        $this->info("🔑 Запрос на восстановление пароля");

        $user = User::where('vk_id', $peerId)->first();

        if (!$user) {
            $this->sendMessage($peerId,
                "❌ Сначала привяжите email:\n" .
                "/reset_email ваш@email.com"
            );
            return;
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('vk_reset_' . $peerId, [
            'code' => $code,
            'user_id' => $user->id,
        ], 900);

        $this->info("📨 Код отправлен: " . $code);

        $this->sendMessage($peerId,
            "🔐 Ваш код: {$code}\n" .
            "Действителен 15 минут."
        );
    }

    private function handleVerifyCode($peerId, $code): void
    {
        $this->info("🔍 Проверка кода: " . $code);

        $data = Cache::get('vk_reset_' . $peerId);

        if (!$data) {
            $this->sendMessage($peerId, "❌ Код истек. Запросите новый /reset_password");
            return;
        }

        if ($data['code'] !== $code) {
            $this->sendMessage($peerId, "❌ Неверный код");
            return;
        }

        $user = User::find($data['user_id']);
        if (!$user) {
            $this->sendMessage($peerId, "❌ Ошибка: пользователь не найден");
            return;
        }

        $token = Str::random(60);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        $url = config('app.url') . '/reset-password/' . $token . '?email=' . urlencode($user->email);

        $this->info("✅ Ссылка отправлена: " . $url);

        $this->sendMessage($peerId,
            "✅ Ссылка для сброса пароля:\n{$url}"
        );

        Cache::forget('vk_reset_' . $peerId);
    }

    private function showTournaments($peerId): void
    {
        $data = $this->vkService->getAvailableTournaments($peerId);
        
        if (!$data) {
            $this->sendMessage($peerId, "❌ Пользователь не найден. Сначала привяжите email: /reset_email");
            return;
        }

        if ($data['tournaments']->isEmpty()) {
            $this->sendMessage($peerId, 
                "📌 Нет доступных турниров для подписки.\n\n" .
                "Все турниры уже активны или вы подписаны на все."
            );
            return;
        }

        $message = "📋 ДОСТУПНЫЕ ТУРНИРЫ\n\n";
        $message .= "Выберите турнир для подписки:\n\n";
        
        foreach ($data['tournaments'] as $tournament) {
            $message .= "🆔 {$tournament->id}. {$tournament->name}\n";
            $message .= "   📍 {$tournament->location}\n";
            $message .= "   📊 Статус: {$tournament->status}\n\n";
        }

        $message .= "📌 Чтобы подписаться, отправьте:\n";
        $message .= "/subscribe_tournament [ID турнира]\n\n";
        $message .= "Пример: /subscribe_tournament 1";

        $this->sendMessage($peerId, $message);
    }

    private function showMyTournaments($peerId): void
    {
        $subscriptions = $this->vkService->getUserSubscriptions($peerId);
        
        if ($subscriptions === null) {
            $this->sendMessage($peerId, "❌ Пользователь не найден");
            return;
        }

        if ($subscriptions->isEmpty()) {
            $this->sendMessage($peerId, 
                "📌 Вы не подписаны ни на один турнир.\n\n" .
                "Посмотреть доступные турниры: /tournaments"
            );
            return;
        }

        $message = "📋 МОИ ПОДПИСКИ\n\n";
        
        foreach ($subscriptions as $sub) {
            $tournament = $sub->tournament;
            $message .= "🔹 {$tournament->name}\n";
            $message .= "   🆔 ID: {$tournament->id}\n";
            $message .= "   📍 {$tournament->location}\n";
            $message .= "   📊 Статус: {$tournament->status}\n";
            $message .= "   🎯 Жеребьёвка: " . ($sub->notify_draw ? '✅' : '❌') . "\n";
            $message .= "   🏁 Начало: " . ($sub->notify_start ? '✅' : '❌') . "\n";
            $message .= "   📊 Результаты: " . ($sub->notify_result ? '✅' : '❌') . "\n\n";
        }

        $message .= "📌 Отписаться: /unsubscribe_tournament [ID турнира]";

        $this->sendMessage($peerId, $message);
    }

    private function subscribeToTournament($peerId, $tournamentId): void
    {
        $tournamentId = trim($tournamentId);
        
        if (!is_numeric($tournamentId)) {
            $this->sendMessage($peerId, "❌ Укажите ID турнира.\nПример: /subscribe_tournament 1");
            return;
        }

        $result = $this->vkService->subscribeToTournament($peerId, (int)$tournamentId);
        
        if ($result['success']) {
            $this->sendMessage($peerId, 
                $result['message'] . "\n\n" .
                "📌 Посмотреть мои подписки: /my_tournaments"
            );
        } else {
            $this->sendMessage($peerId, "❌ " . $result['message']);
        }
    }

    private function unsubscribeFromTournament($peerId, $tournamentId): void
    {
        $tournamentId = trim($tournamentId);
        
        if (!is_numeric($tournamentId)) {
            $this->sendMessage($peerId, "❌ Укажите ID турнира.\nПример: /unsubscribe_tournament 1");
            return;
        }

        $result = $this->vkService->unsubscribeFromTournament($peerId, (int)$tournamentId);
        
        if ($result['success']) {
            $this->sendMessage($peerId, $result['message']);
        } else {
            $this->sendMessage($peerId, "❌ " . $result['message']);
        }
    }

    private function sendMessage($peerId, $message): void
    {
        $result = $this->vkService->sendMessage($peerId, $message);
        
        if ($result && isset($result['error'])) {
            $this->error("❌ Ошибка отправки: " . json_encode($result['error']));
        } elseif ($result) {
            $this->info("✅ Сообщение отправлено");
        } else {
            $this->error("❌ Не удалось отправить сообщение");
        }
    }
}