@extends('layouts.app')

@section('title', 'Смена пароля')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .container-ch {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #f0d9b5;
            border-radius: 8px;
            border: 3px solid #8b4513;
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
            position: relative;
        }

        .container-ch::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(45deg, #b58863 25%, transparent 25%),
                linear-gradient(-45deg, #b58863 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #b58863 75%),
                linear-gradient(-45deg, transparent 75%, #b58863 75%);
            background-size: 40px 40px;
            background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
            opacity: 0.1;
            pointer-events: none;
            z-index: 0;
            border-radius: 8px;
        }

        .container-ch h1 {
            color: #8b4513;
            font-size: 26px;
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .container-ch h1 i {
            margin-right: 10px;
        }

        .container-ch form {
            position: relative;
            z-index: 1;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5a3921;
            font-weight: bold;
            font-family: 'Georgia', serif;
        }

        .form-group .form-control {
            background-color: #fff9f0;
            border: 2px solid #8b4513;
            border-radius: 4px;
            padding: 0.75rem 1rem;
            font-family: 'Georgia', serif;
            color: #5a3921;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group .form-control:focus {
            border-color: #6b3100;
            box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.25);
            outline: none;
            background-color: #ffffff;
        }

        .form-group .form-control.is-invalid {
            border-color: #c62828;
        }

        .invalid-feedback {
            color: #c62828;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            font-style: italic;
        }

        .btn-primary {
            background-color: #8b4513;
            border-color: #6b3100;
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            background-color: #6b3100;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .btn-back {
            display: block;
            background: #6c757d;
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            margin-top: 15px;
            transition: all 0.3s;
            position: relative;
            z-index: 1;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        .btn-back i {
            margin-right: 8px;
        }

        @media (max-width: 576px) {
            .container-ch {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-ch">
    <h1><i class="fas fa-key"></i> Смена пароля</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update-password') }}">
        @csrf

        <div class="form-group">
            <label for="oldPassword">Текущий пароль</label>
            <input type="password" 
                   id="oldPassword" 
                   name="oldPassword" 
                   class="form-control @error('oldPassword') is-invalid @enderror" 
                   placeholder="Введите текущий пароль" 
                   required>
            @error('oldPassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="newPassword">Новый пароль</label>
            <input type="password" 
                   id="newPassword" 
                   name="newPassword" 
                   class="form-control @error('newPassword') is-invalid @enderror" 
                   placeholder="Введите новый пароль (минимум 8 символов)" 
                   required>
            @error('newPassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="newPassword_confirmation">Подтверждение пароля</label>
            <input type="password" 
                   id="newPassword_confirmation" 
                   name="newPassword_confirmation" 
                   class="form-control @error('newPassword_confirmation') is-invalid @enderror" 
                   placeholder="Подтвердите новый пароль" 
                   required>
            @error('newPassword_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Изменить пароль
        </button>
    </form>

    <a href="{{ route('profile.edit') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Назад к редактированию
    </a>
</div>
@endsection