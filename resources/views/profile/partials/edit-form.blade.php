@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
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

    <div class="form-actions">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Сохранить изменения
        </button>
        <a href="{{ route('profile') }}" class="btn-cancel">
            <i class="fas fa-times"></i> Отмена
        </a>
    </div>
</form>

@push('styles')
    <style>
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

        @media (max-width: 600px) {
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endpushы