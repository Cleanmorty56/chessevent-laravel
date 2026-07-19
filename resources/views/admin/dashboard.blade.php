@extends('layouts.app')

@section('title', 'Административная панель')

@push('styles')
    <style>
        .chess-admin-theme {
            padding: 30px 20px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            min-height: 500px;
        }

        .chess-admin-title {
            font-size: 36px;
            font-weight: 700;
            color: #8b4513;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(139, 69, 19, 0.1);
        }

        .chess-admin-subtitle {
            font-size: 18px;
            color: #6b3100;
            margin-bottom: 30px;
            opacity: 0.8;
        }

        .chess-divider {
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, #8b4513, #f0d9b5, #8b4513);
            margin: 30px 0 40px 0;
            border-radius: 2px;
        }

        .chess-button-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 30px 0 50px 0;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .chess-admin-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 30px 35px;
            background: white;
            border: 3px solid #8b4513;
            border-radius: 12px;
            color: #8b4513;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(139, 69, 19, 0.1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .chess-admin-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .chess-admin-btn:hover::before {
            left: 100%;
        }

        .chess-admin-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.25);
            text-decoration: none;
        }

        .chess-admin-btn i {
            font-size: 32px;
            transition: transform 0.3s ease;
        }

        .chess-admin-btn:hover i {
            transform: scale(1.1) rotate(-5deg);
        }

        .chess-admin-btn span {
            position: relative;
            z-index: 1;
        }

        .chess-tournament-btn {
            background: linear-gradient(135deg, #faf5eb 0%, #f0d9b5 100%);
            border-color: #8b4513;
            color: #5d2e0c;
        }

        .chess-tournament-btn:hover {
            background: linear-gradient(135deg, #f0d9b5 0%, #e8c9a0 100%);
            border-color: #6b3100;
            color: #4a2200;
        }

        .chess-planning-btn {
            background: linear-gradient(135deg, #e8f0fe 0%, #d4e4f7 100%);
            border-color: #1a5276;
            color: #1a5276;
        }

        .chess-planning-btn:hover {
            background: linear-gradient(135deg, #d4e4f7 0%, #b8d4f0 100%);
            border-color: #154360;
            color: #0e2f47;
        }

        /* Декоративные шахматные фигуры */
        .chess-board-decoration {
            position: absolute;
            right: 20px;
            bottom: 20px;
            display: flex;
            gap: 15px;
            opacity: 0.1;
            pointer-events: none;
        }

        .chess-piece {
            width: 60px;
            height: 60px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .chess-piece.king {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 45 45'%3E%3Cg fill='%238b4513'%3E%3Cpath d='M22.5 0C21.5 1 19 4 19 7c0 2 1 3 2 4l-4 2-3-3-2 3 4 3-3 2-1-1-2 3 5 3v7l-5 4v5h12v-5l-5-4v-7l5-3-2-3-1 1-3-2 4-3-2-3-3 3-4-2c1-1 2-2 2-4 0-3-2.5-6-3.5-7z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .chess-piece.queen {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 45 45'%3E%3Cg fill='%238b4513'%3E%3Cpath d='M22.5 0c-3 0-5 2-5 4s2 4 5 4 5-2 5-4-2-4-5-4zm-10 12c-1 0-2 1-2 2v4l6-2v6l-8 2-3 10 16 5 16-5-3-10-8-2v-6l6 2v-4c0-1-1-2-2-2h-8c-1 0-2 1-2 2v2l-4-2-4 2v-2c0-1-1-2-2-2h-8z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .chess-piece.rook {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 45 45'%3E%3Cg fill='%238b4513'%3E%3Cpath d='M9 0v5h27V0H9zm-5 7v2h6V7H4zm31 0v2h6V7h-6zm-5 1v12h-5V8h-4v12h-5V8h-4v12h-5V8h-4v12h-5V8H4v29h37V8h-6z'/%3E%3C/g%3E%3C/svg%3E");
        }

        @media (max-width: 768px) {
            .chess-admin-title {
                font-size: 28px;
            }

            .chess-admin-subtitle {
                font-size: 16px;
            }

            .chess-button-container {
                grid-template-columns: 1fr;
                max-width: 100%;
            }

            .chess-admin-btn {
                font-size: 16px;
                padding: 20px 25px;
            }

            .chess-admin-btn i {
                font-size: 28px;
            }

            .chess-board-decoration {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .chess-admin-title {
                font-size: 24px;
            }

            .chess-admin-btn {
                flex-direction: column;
                text-align: center;
                padding: 20px 15px;
            }

            .chess-admin-btn i {
                font-size: 36px;
            }
        }
    </style>
@endpush

@section('content')
<div class="chess-admin-theme">
    <h1 class="chess-admin-title" style="display: flex;
    justify-content: center;" >Административная панель</h1>
    <p class="chess-admin-subtitle" style="display: flex;
    justify-content: center;">Управление шахматной организацией</p>

    <div class="chess-divider"></div>

    <div class="chess-button-container">
        <a href="{{ route('admin.tournaments.index') }}" class="chess-admin-btn chess-tournament-btn">
            <i class="fas fa-chess-board"></i>
            <span>Управление турнирами</span>
        </a>

        <a href="{{ route('admin.planning.index') }}" class="chess-admin-btn chess-planning-btn">
            <i class="fas fa-chess-king"></i>
            <span>Заявки на планирование турниров</span>
        </a>
    </div>

    <div class="chess-board-decoration">
        <div class="chess-piece king"></div>
        <div class="chess-piece queen"></div>
        <div class="chess-piece rook"></div>
    </div>
</div>
<style>
    body {
    display: grid;
    grid-template-rows: auto 1fr auto;
    min-height: 100vh;
    margin: 0;
}
</style>
@endsection