@extends('layouts.app')

@section('title', 'Турниры')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tournaments.css') }}">
    <style>
        /* Ваши стили */
    </style>
@endpush

@section('content')
<div class="tournaments-page">
    <div class="level-titleh">
        <h1>Выберите уровень турнира:</h1>
    </div>

    <div class="levels-container">
        <a href="{{ route('tournaments.index', ['id' => 'all']) }}"
           class="level-filter {{ ($selectedLevelId === null || $selectedLevelId === 'all') ? 'active' : '' }}">
            <h5>Все турниры</h5>
        </a>

        @if(!empty($levels))
            @foreach($levels as $level)
                <a href="{{ route('tournaments.index', ['id' => $level->id]) }}"
                   class="level-filter {{ ($selectedLevelId == $level->id) ? 'active' : '' }}">
                    <div class="level-icon">
                        <span>⭐</span>
                    </div>
                    <h5>{{ $level->name }}</h5>
                </a>
            @endforeach
        @endif
    </div>

    <div class="tournaments-section">
        @if($selectedLevel)
            <h2 class="selected-level-title">
                Турниры уровня: {{ $selectedLevel->name }}
            </h2>
        @elseif($selectedLevelId === 'all' || $selectedLevelId === null)
            <h2 class="selected-level-title">Все турниры</h2>
        @endif

        @if(!empty($tournaments))
            <div class="tournament-list">
                @foreach($tournaments as $t)
                    <div class="tournament-card">
                        <div class="tournament-header">
                            @if(!empty($t->img))
                                <img src="{{ asset('storage/uploads/' . $t->img) }}" alt="{{ $t->name }}">
                            @endif
                            <div class="tournament-level-badge" style="text-align:center; margin-top:10px;">
                                Уровень: {{ $t->level->name ?? 'Не указан' }}
                            </div>
                        </div>

                        <div class="tournament-name">{{ $t->name }}</div>
                        <div class="tournament-desc">{{ $t->description }}</div>

                        <div class="tournament-meta">
                            <div class="meta-item">
                                <span class="meta-label">Режим:</span>
                                <span class="meta-value">{{ $t->gamemode->name ?? 'Не указан' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Локация:</span>
                                <span class="meta-value">{{ $t->location }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Раундов:</span>
                                <span class="meta-value">{{ $t->quantity_rounds }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Статус:</span>
                                <span class="meta-value status-{{ strtolower($t->status) }}">
                                    {{ $t->status }}
                                </span>
                            </div>
                        </div>

                        @auth
                            <div class="tournament-actions">
                                @if($t->isAvailableForRegistration())
                                    @if(in_array($t->id, $userTournamentIds))
                                        <span class="btn btn-success btn-block">Вы зарегистрированы</span>
                                    @else
                                        <form action="{{ route('tournaments.register', $t->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-chess" onclick="return confirm('Вы уверены, что хотите записаться на этот турнир?')">
                                                Записаться
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="btn btn-secondary btn-block">Регистрация закрыта</span>
                                @endif

                                <a href="{{ route('tournaments.draw', $t->id) }}" class="btn btn-info" target="_blank">
                                    📋 Жеребьевка
                                </a>
                            </div>
                        @endauth
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-tournaments">
                <h5>
                    @if($selectedLevel)
                        На уровне "{{ $selectedLevel->name }}" пока нет турниров
                    @else
                        Турниры не найдены
                    @endif
                </h5>
            </div>
        @endif
    </div>
</div>
@endsection