@extends('layouts.app')

@section('title', 'Регистрация')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sign.css') }}">
@endpush
@section('content')
<div class="containereg">
    <h2 style="text-align: center;">Регистрация</h2>

    <form method="POST" action="{{ route('register') }}" id="signup-form" class="pawn-form">
        @csrf

        <div id="pawn-container" style="height: 50px; margin: 20px 0; position: relative;">
            <div id="pawn" style="position: absolute; left: 0; top: 0; font-size: 30px; transition: all 0.5s ease; z-index: 100; color: black;">♙</div>
        </div>

        <div class="form-fields">
            <!-- Логин -->
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" class="form-input @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Имя -->
            <div class="form-group">
                <label for="first_name">Имя</label>
                <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name') }}">
            </div>

            <!-- Фамилия -->
            <div class="form-group">
                <label for="last_name">Фамилия</label>
                <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name') }}">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- ELO -->
            <div class="form-group">
                <label for="elo">ELO (рейтинг)</label>
                <input type="number" id="elo" name="elo" class="form-input" value="{{ old('elo', 1000) }}">
            </div>

            <!-- Регион -->
            <div class="form-group">
                <label for="region_id">Регион</label>
                <select id="region_id" name="region_id" class="form-input">
                    <option value="">Выберите регион</option>
                    @foreach($regions as $id => $name)
                        <option value="{{ $id }}" {{ old('region_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Пароль -->
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Подтверждение пароля -->
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn-reg">Зарегистрироваться</button>
        </div>
    </form>
</div>

<style>
    .containereg {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        background: #faf5eb;
        border-radius: 15px;
        border: 2px solid #8b4513;
    }
    .containereg h2 {
        color: #8b4513;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        color: #5d4037;
        margin-bottom: 5px;
    }
    .form-input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e0d0b0;
        border-radius: 8px;
        font-size: 16px;
        background: #fff9f0;
        transition: all 0.3s;
    }
    .form-input:focus {
        border-color: #8b4513;
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }
    .btn-reg {
        width: 100%;
        padding: 12px;
        background: #8b4513;
        color: #f0e6d2;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-reg:hover {
        background: #6b3100;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    #pawn-container {
        height: 50px;
        margin: 20px 0;
        position: relative;
    }
    #pawn {
        position: absolute;
        left: 0;
        top: 0;
        font-size: 30px;
        transition: all 0.5s ease;
        z-index: 100;
        color: black;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signup-form');
        const pawn = document.getElementById('pawn');
        const inputs = form.querySelectorAll('.form-input');

        function movePawnToInput(input) {
            const rect = input.getBoundingClientRect();
            const formRect = form.getBoundingClientRect();
            const leftPosition = rect.left - formRect.left - 35;
            const topPosition = rect.top - formRect.top + (rect.height / 2);

            pawn.style.left = leftPosition + 'px';
            pawn.style.top = topPosition + 'px';
            pawn.style.transform = 'translateY(-50%) translateY(-10px)';
            setTimeout(() => {
                pawn.style.transform = 'translateY(-50%)';
            }, 200);
        }

        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                movePawnToInput(input);
            });
            input.addEventListener('click', function() {
                movePawnToInput(input);
            });
        });

        form.addEventListener('submit', function() {
            pawn.style.opacity = '0';
            pawn.style.transition = 'opacity 0.5s ease';
        });

        if (inputs.length > 0) {
            movePawnToInput(inputs[0]);
        }
    });
</script>
@endpush
@endsection