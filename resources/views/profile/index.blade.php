@extends('layouts.app')

@section('title', 'Профиль пользователя')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .profile-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Georgia', serif;
        }

        /* Шапка профиля */
        .profile-header {
            display: flex;
            gap: 30px;
            background: linear-gradient(135deg, #8b4513, #6b3100);
            padding: 30px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
            flex-wrap: wrap;
            align-items: center;
        }

        .profile-avatar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #f0d9b5;
            background: white;
            object-fit: cover;
        }

        .avatar-name {
            font-size: 14px;
            color: #f0d9b5;
            text-align: center;
        }

        .profile-info-header {
            flex: 1;
        }

        .profile-info-header h1 {
            font-size: 32px;
            margin: 0 0 5px 0;
            color: #f0d9b5;
        }

        .profile-fullname {
            font-size: 18px;
            color: #e0c9a0;
            margin-bottom: 10px;
        }

        .profile-badges {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .badge-elo {
            background: #f0d9b5;
            color: #8b4513;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
        }

        .badge-level {
            background: #1a5276;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 16px;
        }

        .profile-actions-header {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-action:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Сетка */
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .profile-grid .full-width {
            grid-column: 1 / -1;
        }

        /* Карточки */
        .info-card, .stats-card, .elo-card, .games-card, .tournaments-card {
            background: white;
            border: 2px solid #8b4513;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);
        }

        .info-card h3, .stats-card h3, .elo-card h3, .games-card h3, .tournaments-card h3 {
            color: #8b4513;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            border-bottom: 2px solid #f0d9b5;
            padding-bottom: 10px;
        }

        .info-card h3 i, .stats-card h3 i, .elo-card h3 i, .games-card h3 i, .tournaments-card h3 i {
            margin-right: 10px;
        }

        /* Информация */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #8b4513;
        }

        .info-value {
            color: #555;
        }

        .info-value .not-specified {
            color: #999;
            font-style: italic;
        }

        .elo-value {
            font-weight: bold;
            color: #8b4513;
            font-size: 18px;
        }

        /* Статистика */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            text-align: center;
        }

        .stat-item {
            padding: 15px;
            border-radius: 10px;
            background: #faf5eb;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #8b4513;
        }

        .stat-label {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .stat-wins .stat-number { color: #2e7d32; }
        .stat-losses .stat-number { color: #c62828; }
        .stat-draws .stat-number { color: #f57c00; }
        .stat-rate .stat-number { color: #1a5276; }

        /* История ELO */
        .elo-timeline {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .elo-point {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #faf5eb;
            border-radius: 8px;
            border-left: 4px solid #8b4513;
        }

        .elo-date {
            color: #666;
            font-size: 14px;
        }

        .elo-change {
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .elo-change.up {
            color: #2e7d32;
            background: #e8f5e9;
        }

        .elo-change.down {
            color: #c62828;
            background: #ffebee;
        }

        .elo-values {
            color: #555;
            font-weight: bold;
        }

        /* История игр */
        .games-scroll {
            overflow-x: auto;
        }

        .games-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .games-table thead {
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
        }

        .games-table th {
            padding: 12px;
            text-align: left;
        }

        .games-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0d0b0;
        }

        .games-table tbody tr:hover {
            background: #faf5eb;
        }

        .game-date {
            color: #666;
            font-size: 13px;
        }

        .game-opponent {
            font-weight: 500;
        }

        .game-color {
            font-weight: bold;
        }

        .game-color.white { color: #555; }
        .game-color.black { color: #000; }

        .game-result {
            font-weight: bold;
        }

        .result-win { color: #2e7d32; }
        .result-loss { color: #c62828; }
        .result-draw { color: #f57c00; }

        .btn-pgn {
            background: #5d4037;
            color: white;
            padding: 4px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s;
        }

        .btn-pgn:hover {
            background: #3e2723;
            color: white;
            text-decoration: none;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }

        /* Турниры */
        .tournaments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .tournament-item {
            background: #faf5eb;
            border: 1px solid #e0d0b0;
            border-radius: 10px;
            padding: 15px;
            transition: all 0.3s;
        }

        .tournament-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
        }

        .tournament-item h4 {
            color: #8b4513;
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        .tournament-desc {
            color: #666;
            font-size: 13px;
            margin: 0 0 10px 0;
        }

        .tournament-status {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-запланирован { background: #fff3cd; color: #856404; }
        .status-впроцессе { background: #d1ecf1; color: #0c5460; }
        .status-завершен { background: #d4edda; color: #155724; }

        .tournament-reg-date {
            font-size: 13px;
            color: #666;
            margin: 8px 0;
        }

        .btn-tournament {
            display: inline-block;
            background: #8b4513;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-tournament:hover {
            background: #6b3100;
            color: white;
            text-decoration: none;
        }

        /* Адаптивность */
        @media (max-width: 992px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-row {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .profile-info-header h1 {
                font-size: 24px;
            }

            .profile-badges {
                justify-content: center;
            }

            .profile-actions-header {
                justify-content: center;
            }

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .tournaments-grid {
                grid-template-columns: 1fr;
            }

            .info-row {
                flex-direction: column;
                gap: 3px;
            }
        }

        @media (max-width: 480px) {
            .profile-avatar img {
                width: 80px;
                height: 80px;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-number {
                font-size: 22px;
            }
        }
    </style>
@endpush

@section('content')
<div class="profile-page">
    <!-- Шапка профиля -->
    <div class="profile-header">
        <div class="profile-avatar">
            <img src="{{ asset('img/free-icon-user-2550260.png') }}" alt="avatar">
            @if(!empty($user->first_name) || !empty($user->last_name))
                <div class="avatar-name">
                    {{ $user->first_name }} {{ $user->last_name }}
                </div>
            @endif
        </div>
        <div class="profile-info-header">
            <h1>{{ $user->username }}</h1>
            @if(!empty($user->first_name) || !empty($user->last_name))
                <div class="profile-fullname">
                    <i class="fas fa-user-circle"></i> {{ $user->first_name }} {{ $user->last_name }}
                </div>
            @endif
            <div class="profile-badges">
                <span class="badge-elo">Рейтинг ELO: {{ $user->elo ?? 1000 }}</span>
                <span class="badge-level">
                    @php
                        $elo = $user->elo ?? 1000;
                        if ($elo >= 2000) echo 'Гроссмейстер';
                        elseif ($elo >= 1800) echo 'Мастер';
                        elseif ($elo >= 1600) echo 'Эксперт';
                        elseif ($elo >= 1400) echo 'Продвинутый';
                        elseif ($elo >= 1200) echo 'Средний';
                        elseif ($elo >= 1000) echo 'Начинающий';
                        else echo 'Новичок';
                    @endphp
                </span>
            </div>
            <div class="profile-actions-header">
                <a href="{{ route('profile.edit') }}" class="btn-action">
                    <i class="fas fa-user-edit"></i> Редактировать профиль
                </a>
                <a href="{{ route('profile.change-password') }}" class="btn-action">
                    <i class="fas fa-key"></i> Сменить пароль
                </a>
            </div>
        </div>
    </div>

    <!-- Сетка с карточками -->
    <div class="profile-grid">
        <!-- Карточка информации -->
        <div class="info-card">
            <h3><i class="fas fa-user-circle"></i> О игроке</h3>
            <div class="info-list">
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user"></i> Логин:</span>
                    <span class="info-value">{{ $user->username }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user-tag"></i> Имя:</span>
                    <span class="info-value">{{ $user->first_name ?: '<span class="not-specified">не указано</span>' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-user-tag"></i> Фамилия:</span>
                    <span class="info-value">{{ $user->last_name ?: '<span class="not-specified">не указано</span>' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-envelope"></i> Email:</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar-alt"></i> Регистрация:</span>
                    <span class="info-value">{{ $user->created_at ? $user->created_at->format('d.m.Y') : '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-chess-queen"></i> Рейтинг ELO:</span>
                    <span class="info-value elo-value">{{ $user->elo ?? 1000 }}</span>
                </div>
            </div>
        </div>

        <!-- Карточка статистики -->
        <div class="stats-card">
            <h3><i class="fas fa-chart-simple"></i> Статистика</h3>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalGames }}</div>
                    <div class="stat-label">Всего игр</div>
                </div>
                <div class="stat-item stat-wins">
                    <div class="stat-number">{{ $wins }}</div>
                    <div class="stat-label">Победы</div>
                </div>
                <div class="stat-item stat-losses">
                    <div class="stat-number">{{ $losses }}</div>
                    <div class="stat-label">Поражения</div>
                </div>
                <div class="stat-item stat-draws">
                    <div class="stat-number">{{ $draws }}</div>
                    <div class="stat-label">Ничьи</div>
                </div>
                <div class="stat-item stat-rate">
                    <div class="stat-number">{{ $winRate }}%</div>
                    <div class="stat-label">% побед</div>
                </div>
            </div>
        </div>

        <!-- История ELO -->
        <div class="elo-card">
            <h3><i class="fas fa-chart-line"></i> История рейтинга</h3>
            @if($eloHistory->isNotEmpty())
                <div class="elo-timeline">
                    @foreach($eloHistory as $h)
                        <div class="elo-point">
                            <div class="elo-date">{{ $h->created_at ? $h->created_at->format('d.m.Y') : '—' }}</div>
                            <div class="elo-change {{ $h->change > 0 ? 'up' : ($h->change < 0 ? 'down' : '') }}">
                                {{ $h->change > 0 ? '+' : '' }}{{ $h->change }}
                            </div>
                            <div class="elo-values">
                                {{ $h->elo_before }} → {{ $h->elo_after }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-message">Нет истории рейтинга</div>
            @endif
        </div>

        <!-- История игр -->
        <div class="games-card full-width">
            <h3><i class="fas fa-history"></i> История игр</h3>
            @if($games->isNotEmpty())
                <div class="games-scroll">
                    <table class="games-table">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Соперник</th>
                                <th>Цвет</th>
                                <th>Результат</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                                @php
                                    $isWhite = $game->white_user_id == $user->id;
                                    $opponent = $isWhite ? $game->blackUser : $game->whiteUser;
                                    $myColor = $isWhite ? 'Белые' : 'Черные';
                                    
                                    if ($game->winner_id == $user->id) {
                                        $result = 'Победа';
                                        $resultClass = 'result-win';
                                    } elseif ($game->status == 'draw') {
                                        $result = 'Ничья';
                                        $resultClass = 'result-draw';
                                    } elseif ($game->winner_id !== null && $game->winner_id != $user->id) {
                                        $result = 'Поражение';
                                        $resultClass = 'result-loss';
                                    } else {
                                        $result = '—';
                                        $resultClass = '';
                                    }
                                @endphp
                                <tr>
                                    <td class="game-date">{{ $game->finished_at ? $game->finished_at->format('d.m.Y') : ($game->started_at ? $game->started_at->format('d.m.Y') : '—') }}</td>
                                    <td class="game-opponent">{{ $opponent->username ?? 'Неизвестно' }}</td>
                                    <td class="game-color {{ $isWhite ? 'white' : 'black' }}">{{ $myColor }}</td>
                                    <td class="game-result {{ $resultClass }}">{{ $result }}</td>
                                    <td>
                                        <a href="{{ route('profile.download-pgn', $game->id) }}" class="btn-pgn" target="_blank">PGN</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-message">Нет сыгранных партий</div>
            @endif
        </div>

        <!-- Турниры -->
        <div class="tournaments-card full-width">
            <h3><i class="fas fa-trophy"></i> Мои турниры</h3>
            @if($tournaments->isNotEmpty())
                <div class="tournaments-grid">
                    @foreach($tournaments as $t)
                        @php
                            $registration = $registrations->get($t->id);
                            $regDate = $registration && $registration->registration_date 
                                ? \Carbon\Carbon::parse($registration->registration_date)->format('d.m.Y') 
                                : '-';
                        @endphp
                        <div class="tournament-item">
                            <h4>{{ $t->name }}</h4>
                            <p class="tournament-desc">{{ $t->description ?: 'Описание отсутствует' }}</p>
                            <span class="tournament-status status-{{ strtolower(str_replace(' ', '', $t->status)) }}">
                                {{ $t->status }}
                            </span>
                            <div class="tournament-reg-date">
                                <i class="fas fa-calendar-plus"></i>
                                {{ $regDate }}
                            </div>
                            <a href="{{ route('tournaments.draw', $t->id) }}" class="btn-tournament" target="_blank">
                                Жеребьевка
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-message">Вы не зарегистрированы ни на один турнир</div>
            @endif
        </div>
    </div>
</div>
@endsection