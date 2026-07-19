@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="site-login">
        <h1>🔐 Вход</h1>
        <p>Пожалуйста, заполните следующие поля для входа в систему:</p>

        <div class="row">
            <div class="col-lg-5" style="width: 100%;">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="username">Логин</label>
                        <input type="text" id="username" name="username" class="form-input @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus>
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" style="margin-bottom: 0;">Запомнить меня</label>
                    </div>

                    <!-- Кнопка Войти -->
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Войти
                    </button>

                    <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0d0b0;">
                        <a href="https://vk.com/club240232906" target="_blank" style="color: #2787F5; text-decoration: none; font-size: 16px;">
                            <i class="fab fa-vk"></i> Восстановить через VK
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
/* ============================================
   СТРАНИЦА ВХОДА / РЕГИСТРАЦИИ
   ============================================ */
.site-login {
    max-width: 500px;
    margin: 40px auto;
    padding: 35px 40px;
    background: #faf5eb;
    border-radius: 15px;
    border: 2px solid #8b4513;
    box-shadow: 0 8px 25px rgba(139, 69, 19, 0.15);
}

.site-login h1 {
    color: #8b4513;
    text-align: center;
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: bold;
    font-family: 'Georgia', serif;
}

.site-login .subtitle {
    color: #8b4513;
    text-align: center;
    font-size: 15px;
    margin-bottom: 30px;
    opacity: 0.8;
    font-style: italic;
}

/* ===== ФОРМА ===== */
.site-login .form-group {
    margin-bottom: 20px;
}

.site-login .form-group label {
    display: block;
    font-weight: bold;
    color: #5d4037;
    margin-bottom: 6px;
    font-size: 14px;
}

.site-login .form-group .form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0d0b0;
    border-radius: 8px;
    font-size: 16px;
    background: #fff9f0;
    transition: all 0.3s ease;
    color: #3e2723;
    box-sizing: border-box;
}

.site-login .form-group .form-input:focus {
    border-color: #8b4513;
    outline: none;
    box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.12);
    background: #ffffff;
}

.site-login .form-group .form-input.is-invalid {
    border-color: #dc3545;
}

.site-login .form-group .invalid-feedback {
    color: #dc3545;
    font-size: 13px;
    margin-top: 6px;
    display: block;
}

/* ===== ЧЕКБОКС "Запомнить меня" ===== */
.site-login .form-check {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 18px 0 10px 0;
}

.site-login .form-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #8b4513;
    cursor: pointer;
}

.site-login .form-check label {
    color: #5d4037;
    font-size: 14px;
    cursor: pointer;
    margin: 0;
}

/* ===== КНОПКА ВХОДА ===== */
.site-login .btn-login {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #8b4513, #6b3100);
    color: #f0e6d2;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
    letter-spacing: 0.5px;
}

.site-login .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(139, 69, 19, 0.35);
    background: linear-gradient(135deg, #9b5523, #7b4100);
}

.site-login .btn-login:active {
    transform: translateY(0);
}

/* ===== ВОССТАНОВЛЕНИЕ ЧЕРЕЗ VK ===== */
.site-login .vk-divider {
    display: flex;
    align-items: center;
    margin: 25px 0 20px 0;
    gap: 15px;
}

.site-login .vk-divider::before,
.site-login .vk-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e0d0b0;
}

.site-login .vk-divider span {
    color: #8b4513;
    font-size: 13px;
    white-space: nowrap;
    opacity: 0.7;
}

.site-login .btn-vk {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 12px;
    background: #2787F5;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}

.site-login .btn-vk:hover {
    background: #1a6bc4;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(39, 135, 245, 0.35);
    text-decoration: none;
}

.site-login .btn-vk:active {
    transform: translateY(0);
}

.site-login .btn-vk i {
    font-size: 20px;
}

/* ===== ССЫЛКА НА РЕГИСТРАЦИЮ ===== */
.site-login .register-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
    color: #666;
}

.site-login .register-link a {
    color: #8b4513;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.3s;
}

.site-login .register-link a:hover {
    color: #6b3100;
    text-decoration: underline;
}

/* ===== ОШИБКИ ===== */
.site-login .alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
}

.site-login .alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
}

/* ===== АДАПТИВНОСТЬ ===== */
@media (max-width: 576px) {
    .site-login {
        padding: 25px 20px;
        margin: 20px 15px;
    }

    .site-login h1 {
        font-size: 22px;
    }

    .site-login .form-group .form-input {
        padding: 10px 14px;
        font-size: 15px;
    }

    .site-login .btn-login {
        padding: 12px;
        font-size: 16px;
    }

    .site-login .btn-vk {
        padding: 10px;
        font-size: 14px;
    }
}

@media (min-width: 1920px) {
    .site-login {
        max-width: 600px;
        padding: 45px 55px;
    }

    .site-login h1 {
        font-size: 34px;
    }

    .site-login .form-group .form-input {
        padding: 14px 20px;
        font-size: 18px;
    }

    .site-login .btn-login {
        padding: 16px;
        font-size: 20px;
    }
}

body {
    display: grid;
    grid-template-rows: auto 1fr auto;
    min-height: 100vh;
    margin: 0;
}
    </style>
@endsection