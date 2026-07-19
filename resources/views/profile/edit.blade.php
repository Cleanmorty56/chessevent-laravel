@extends('layouts.app')

@section('title', 'Редактирование профиля')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .profile-update-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 40px 20px;
            background: #f5f0e8;
            min-height: 100vh;
        }

        .profile-update-card {
            background: white;
            border-radius: 20px;
            padding: 35px;
            border: 2px solid #8b4513;
            box-shadow: 0 10px 30px rgba(139, 69, 19, 0.1);
        }

        .profile-update-card h1 {
            color: #8b4513;
            font-size: 28px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #8b4513;
            text-align: center;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #faf5eb;
            border-radius: 15px;
        }

        .form-section h3 {
            color: #8b4513;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e0d0b0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #5d4037;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            border: 2px solid #e0d0b0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            border-color: #8b4513;
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        .form-control.is-invalid {
            border-color: #c62828;
        }

        .invalid-feedback {
            color: #c62828;
            font-size: 13px;
            margin-top: 5px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 0;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .alert a {
            color: #0c5460;
            font-weight: bold;
        }

        .alert a:hover {
            color: #062c33;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn-save {
            background: #8b4513;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
        }

        .btn-save:hover {
            background: #6b3100;
            transform: translateY(-2px);
            color: white;
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #666;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #ccc;
            color: #444;
            text-decoration: none;
        }

        .btn-change-password {
            background: #1a5276;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s;
            flex: 1;
            text-align: center;
        }

        .btn-change-password:hover {
            background: #154360;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .btn-vk-link {
            display: inline-block;
            background: #2787F5;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            margin-top: 5px;
        }

        .btn-vk-link:hover {
            background: #1a6bc4;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .vk-connected {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .vk-username {
            background: #e3f2fd;
            padding: 3px 12px;
            border-radius: 12px;
            font-weight: bold;
            color: #0d47a1;
        }

        @media (max-width: 600px) {
            .profile-update-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-save, .btn-cancel, .btn-change-password {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="profile-update-container">
    <div class="profile-update-card">
        <h1><i class="fas fa-user-edit"></i> Редактирование профиля</h1>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:20px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:15px 20px;border-radius:10px;">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-section">
                <h3><i class="fas fa-user-circle"></i> Личная информация</h3>

                <div class="form-group">
                    <label for="first_name">Имя</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name', $user->first_name) }}" 
                           placeholder="Введите ваше имя">
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Фамилия</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name', $user->last_name) }}" 
                           placeholder="Введите вашу фамилию">
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-envelope"></i> Контактная информация</h3>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" 
                           placeholder="example@mail.com" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- ===== БЛОК VK ===== -->
            <div class="form-section">
                <h3><i class="fab fa-vk"></i> ВКонтакте</h3>
                
                @if($user->vk_id)
                    <div class="alert alert-success">
                        <div class="vk-connected">
                            <i class="fab fa-vk" style="font-size:20px;color:#2787F5;"></i>
                            <span>Ваш VK аккаунт связан!</span>
                        </div>
                        <div style="margin-top:8px;font-size:14px;color:#155724;">
                            <i class="fas fa-info-circle"></i> 
                            Вы можете восстанавливать пароль через VK бота
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fab fa-vk" style="font-size:18px;color:#2787F5;"></i>
                        <strong>Свяжите VK для восстановления пароля</strong>
                        <br><br>
                        <ol style="margin:0;padding-left:20px;">
                            <li>Напишите боту в VK: 
                                <a href="https://vk.com/club240232906" target="_blank" class="btn-vk-link">
                                    <i class="fab fa-vk"></i> Перейти в сообщество
                                </a>
                            </li>
                            <li style="margin-top:8px;">Отправьте боту: <strong>/reset_email {{ $user->email }}</strong></li>
                            <li style="margin-top:8px;">Бот автоматически свяжет аккаунты</li>
                        </ol>
                        <div style="margin-top:12px;font-size:13px;color:#0c5460;">
                            <i class="fas fa-shield-alt"></i> 
                            Ваш VK ID будет использоваться только для восстановления пароля
                        </div>
                    </div>
                @endif
            </div>
            <!-- ===== КОНЕЦ БЛОКА VK ===== -->

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Сохранить изменения
                </button>
                
                <a href="{{ route('profile.change-password') }}" class="btn-change-password">
                    <i class="fas fa-key"></i> Сменить пароль
                </a>

                <a href="{{ route('profile') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection