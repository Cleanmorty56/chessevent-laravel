@extends('layouts.app')

@section('title', 'Просмотр турнира')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .tournament-detail {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            border: 2px solid #8b4513;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .detail-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0d0b0;
            margin-bottom: 20px;
        }

        .detail-header img {
            max-width: 200px;
            border-radius: 10px;
            border: 3px solid #8b4513;
        }

        .detail-field {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #e0d0b0;
        }

        .detail-field:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #8b4513;
            font-size: 15px;
        }

        .detail-value {
            color: #555;
            font-size: 15px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
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

        .btn-edit {
            background: #8b4513;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-edit:hover {
            background: #6b3100;
            color: white;
            text-decoration: none;
        }

        .detail-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-завершен { background: #d4edda; color: #155724; }
        .status-запланирован { background: #fff3cd; color: #856404; }
        .status-впроцессе { background: #d1ecf1; color: #0c5460; }

        @media (max-width: 768px) {
            .tournament-detail { padding: 20px; }
            .detail-field { flex-direction: column; align-items: flex-start; gap: 5px; }
            .detail-actions { flex-direction: column; }
            .btn-back, .btn-edit { width: 100%; text-align: center; }
        }
    </style>
@endpush

@section('content')
<div class="tournament-detail">
    <div class="detail-header">
        @if($tournament->img)
            <img src="{{ asset('storage/uploads/' . $tournament->img) }}" alt="{{ $tournament->name }}">
        @endif
        <h1 style="color:#8b4513;margin-top:15px;">{{ $tournament->name }}</h1>
        <div style="margin-top:10px;">
            <span class="status-badge status-{{ strtolower(str_replace(' ', '', $tournament->status)) }}">
                {{ $tournament->status }}
            </span>
        </div>
    </div>

    <div class="detail-field">
        <span class="detail-label">📝 Описание:</span>
        <span class="detail-value">{{ $tournament->description ?? '—' }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">🏆 Уровень:</span>
        <span class="detail-value">{{ $tournament->level->name ?? '—' }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">🎮 Режим игры:</span>
        <span class="detail-value">{{ $tournament->gamemode->name ?? '—' }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">📍 Место проведения:</span>
        <span class="detail-value">{{ $tournament->location }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">🔄 Количество туров:</span>
        <span class="detail-value">{{ $tournament->quantity_rounds }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">👥 Участников:</span>
        <span class="detail-value">{{ $tournament->users->count() }}</span>
    </div>

    <div class="detail-actions">
    <a href="{{ route('admin.tournaments.manage', $tournament->id) }}" class="btn-manage" style="background:#2e7d32;color:white;padding:10px 25px;border:none;border-radius:8px;font-size:16px;cursor:pointer;text-decoration:none;display:inline-block;transition:all 0.3s;">
        ⚙️ Управление турниром
    </a>
     <a href="{{ route('admin.tournaments.draw-view', $tournament->id) }}" class="btn-draw" style="background:#1a5276;color:white;padding:10px 25px;border:none;border-radius:8px;font-size:16px;cursor:pointer;text-decoration:none;display:inline-block;transition:all 0.3s;">
        📋 Просмотр жеребьевки
    </a>
    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn-edit">✏️ Редактировать</a>
    <a href="{{ route('admin.tournaments.index') }}" class="btn-back">⬅ Назад</a>
</div>
@endsection