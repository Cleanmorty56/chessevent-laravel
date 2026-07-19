@extends('layouts.app')

@section('title', 'Редактирование турнира')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .chess-form {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #8b4513;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: bold;
            color: #8b4513;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #8b4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }

        .form-control-file {
            padding: 10px 0;
        }

        .current-image {
            margin: 10px 0;
            padding: 10px;
            background: #f8f1e5;
            border-radius: 8px;
            text-align: center;
        }

        .current-image img {
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid #8b4513;
        }

        .btn-submit {
            background: #8b4513;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #6b3100;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .text-danger {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .chess-form { padding: 20px; }
            .form-actions { flex-direction: column; }
            .btn-submit, .btn-back { width: 100%; text-align: center; }
        }
    </style>
@endpush

@section('content')
<div class="chess-form">
    <h1 style="color:#8b4513;text-align:center;margin-bottom:30px;">Редактирование турнира</h1>

    <form action="{{ route('admin.tournaments.update', $tournament->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Название турнира *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $tournament->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                      rows="4">{{ old('description', $tournament->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Уровень *</label>
            <select name="level_id" class="form-control @error('level_id') is-invalid @enderror" required>
                <option value="">Выберите уровень</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $tournament->level_id) == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
            @error('level_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Режим игры *</label>
            <select name="gamemode_id" class="form-control @error('gamemode_id') is-invalid @enderror" required>
                <option value="">Выберите режим</option>
                @foreach($gamemodes as $gamemode)
                    <option value="{{ $gamemode->id }}" {{ old('gamemode_id', $tournament->gamemode_id) == $gamemode->id ? 'selected' : '' }}>
                        {{ $gamemode->name }}
                    </option>
                @endforeach
            </select>
            @error('gamemode_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Место проведения *</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                   value="{{ old('location', $tournament->location) }}" required>
            @error('location')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Количество туров *</label>
            <input type="number" name="quantity_rounds" class="form-control @error('quantity_rounds') is-invalid @enderror" 
                   value="{{ old('quantity_rounds', $tournament->quantity_rounds) }}" min="1" required>
            @error('quantity_rounds')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Статус *</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                <option value="Запланирован" {{ old('status', $tournament->status) == 'Запланирован' ? 'selected' : '' }}>Запланирован</option>
                <option value="В процессе" {{ old('status', $tournament->status) == 'В процессе' ? 'selected' : '' }}>В процессе</option>
                <option value="Завершен" {{ old('status', $tournament->status) == 'Завершен' ? 'selected' : '' }}>Завершен</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Изображение</label>
            @if($tournament->img)
                <div class="current-image">
                    <p>Текущее изображение:</p>
                    <img src="{{ asset('storage/uploads/' . $tournament->img) }}" alt="{{ $tournament->name }}">
                </div>
            @endif
            <input type="file" name="img" class="form-control-file @error('img') is-invalid @enderror" 
                   accept="image/*">
            <small style="color:#666;">Оставьте пустым, если не хотите менять изображение</small>
            @error('img')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Обновить турнир</button>
            <a href="{{ route('admin.tournaments.index') }}" class="btn-back">Отмена</a>
        </div>
    </form>
</div>
@endsection