<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сброс пароля</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            max-width: 450px;
            width: 100%;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        h2 {
            font-size: 24px;
            color: #1a1a2e;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            transition: border-color 0.2s;
            background: #fafbfc;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        }

        .password-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #007bff;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
        }

        button:hover {
            background: #0056b3;
        }

        button:active {
            transform: scale(0.98);
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error-box p {
            margin: 4px 0;
            font-size: 14px;
        }

        .success-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success-box p {
            margin: 4px 0;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #9ca3af;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        input[type="hidden"] {
            display: none;
        }

        @media (max-width: 480px) {
            .container {
                padding: 24px;
                margin: 16px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Восстановление пароля</h2>
        <p class="subtitle">Введите новый пароль для вашей учетной записи</p>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="success-box">
                <p>✅ {{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') ?? $token ?? '' }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="form-group">
                <label for="password">Новый пароль</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    minlength="8"
                    placeholder="Минимум 8 символов"
                >
                <div class="password-hint">Пароль должен содержать минимум 8 символов</div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    placeholder="Повторите пароль"
                >
            </div>

            <button type="submit">Сбросить пароль</button>
        </form>

        <div class="footer">
            <a href="/login">Вернуться к входу</a>
        </div>
    </div>
</body>
</html>