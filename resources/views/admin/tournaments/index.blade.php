@extends('layouts.app')

@section('title', 'Управление турнирами')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .chess-theme {
            font-family: 'Georgia', serif;
            background-color: #f8f1e5;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(139, 69, 19, 0.2);
        }

        .chess-main-title {
            color: #8b4513;
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .btn-chess {
            background-color: #8b4513;
            border-color: #6b3100;
            color: white;
            font-weight: bold;
            padding: 8px 20px;
            border-radius: 5px;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .btn-chess:hover {
            background-color: #6b3100;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .tournament-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .tournament-card {
            background: white;
            border: 2px solid #8b4513;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .tournament-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }

        .tournament-card-header {
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
            padding: 15px;
            text-align: center;
            position: relative;
        }

        .tournament-id {
            position: absolute;
            top: 10px;
            left: 15px;
            background: rgba(255,255,255,0.2);
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .tournament-image {
            margin-bottom: 10px;
        }

        .tournament-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            background: white;
        }

        .tournament-name {
            font-size: 18px;
            margin: 10px 0 5px;
            color: white;
        }

        .tournament-card-body {
            padding: 15px;
            background: #faf5eb;
        }

        .tournament-field {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e0d0b0;
        }

        .tournament-field:last-child {
            border-bottom: none;
        }

        .field-label {
            font-weight: bold;
            color: #8b4513;
            font-size: 13px;
        }

        .field-value {
            color: #555;
            font-size: 13px;
            text-align: right;
            max-width: 60%;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-завершен {
            background: #d4edda;
            color: #155724;
        }

        .status-запланирован {
            background: #fff3cd;
            color: #856404;
        }

        .status-впроцессе {
            background: #d1ecf1;
            color: #0c5460;
        }

        .tournament-card-footer {
            display: flex;
            gap: 10px;
            padding: 12px 15px;
            background: #f8f1e5;
            border-top: 1px solid #e0d0b0;
        }

        .btn-card-view {
            flex: 1;
            background: #5d4037;
            color: white;
            text-decoration: none;
            padding: 8px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-card-view:hover {
            background: #3e2723;
            color: white;
            text-decoration: none;
            transform: scale(1.02);
        }

        .btn-card-edit {
            flex: 1;
            background: #8b4513;
            color: white;
            text-decoration: none;
            padding: 8px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-card-edit:hover {
            background: #6b3100;
            color: white;
            text-decoration: none;
            transform: scale(1.02);
        }

        .btn-card-delete {
            flex: 1;
            background: #c62828;
            color: white;
            text-decoration: none;
            padding: 8px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-card-delete:hover {
            background: #b71c1c;
            color: white;
            text-decoration: none;
            transform: scale(1.02);
        }

        .chess-summary {
            color: #8b4513;
            font-style: italic;
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e0d0b0;
        }

        @media (max-width: 768px) {
            .chess-theme { padding: 15px; }
            .tournament-cards { grid-template-columns: 1fr; gap: 20px; }
            .tournament-name { font-size: 16px; }
            .field-label, .field-value { font-size: 12px; }
            .btn-card-edit, .btn-card-delete, .btn-card-view { padding: 6px; font-size: 12px; }
        }

        @media (max-width: 480px) {
            .chess-theme { padding: 10px; }
            .chess-main-title { font-size: 22px; }
            .tournament-card-header { padding: 12px; }
            .tournament-img { width: 60px; height: 60px; }
            .tournament-name { font-size: 14px; }
            .tournament-field { flex-direction: column; align-items: flex-start; gap: 5px; }
            .field-value { text-align: left; max-width: 100%; }
            .tournament-card-footer { flex-wrap: wrap; }
            .btn-card-view, .btn-card-edit, .btn-card-delete { flex: 1 1 100%; }
        }
    </style>
@endpush

@section('content')
<div class="chess-theme">
    <h1 class="chess-main-title">Управление турнирами</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p>
        <a href="{{ route('admin.tournaments.create') }}" class="btn-chess">
            ➕ Добавить турнир
        </a>
    </p>

    <div class="tournament-cards">
        @forelse($tournaments as $model)
            <div class="tournament-card">
                <div class="tournament-card-header">
                    <div class="tournament-id">#{{ $model->id }}</div>
                    <div class="tournament-image">
                        @if($model->img)
                            <img src="{{ asset('storage/uploads/' . $model->img) }}" alt="{{ $model->name }}">
                        @else
                            <div class="tournament-img" style="display:flex;align-items:center;justify-content:center;background:#f0d9b5;color:#8b4513;font-size:30px;">
                                ♟️
                            </div>
                        @endif
                    </div>
                    <h3 class="tournament-name">{{ $model->name }}</h3>
                </div>
                
                <div class="tournament-card-body">
                    <div class="tournament-field">
                        <span class="field-label">📝 Описание:</span>
                        <span class="field-value">{{ $model->description ?? '—' }}</span>
                    </div>
                    
                    <div class="tournament-field">
                        <span class="field-label">🎮 Режим:</span>
                        <span class="field-value">{{ $model->gamemode->name ?? '—' }}</span>
                    </div>
                    
                    <div class="tournament-field">
                        <span class="field-label">📍 Место:</span>
                        <span class="field-value">{{ $model->location }}</span>
                    </div>
                    
                    <div class="tournament-field">
                        <span class="field-label">🔄 Туры:</span>
                        <span class="field-value">{{ $model->quantity_rounds }}</span>
                    </div>
                    
                    <div class="tournament-field">
                        <span class="field-label">📊 Статус:</span>
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '', $model->status)) }}">
                            {{ $model->status }}
                        </span>
                    </div>
                    
                    <div class="tournament-field">
                        <span class="field-label">🏆 Уровень:</span>
                        <span class="field-value">{{ $model->level->name ?? '—' }}</span>
                    </div>
                </div>
                
                <div class="tournament-card-footer">
                    <a href="{{ route('admin.tournaments.show', $model->id) }}" class="btn-card-view">👁️ Просмотр</a>
                    <a href="{{ route('admin.tournaments.edit', $model->id) }}" class="btn-card-edit">✏️ Редактировать</a>
                    <form action="{{ route('admin.tournaments.destroy', $model->id) }}" method="POST" style="flex:1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-card-delete" onclick="return confirm('Вы уверены, что хотите удалить этот турнир?')">
                            🗑️ Удалить
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:40px;background:#faf5eb;border-radius:12px;border:2px solid #8b4513;">
                <h3 style="color:#8b4513;">Турниры не найдены</h3>
                <p style="color:#666;">Создайте первый турнир, нажав кнопку "Добавить турнир"</p>
            </div>
        @endforelse
    </div>

    <div class="chess-summary">
        Показано записей: {{ $tournaments->count() }}
    </div>
</div>
@endsection