@extends('layouts.app')

@section('title', 'Жеребьевка: ' . $tournament->name)
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .tournament-draw {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .no-print {
            margin-bottom: 20px;
        }

        .btn-chess {
            background: linear-gradient(to bottom, #8b4513, #6b3100);
            color: #f0e6d2 !important;
            border: 2px solid #5d4037;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            padding: 10px 20px;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-chess:hover {
            background: linear-gradient(to bottom, #a0522d, #8b4513);
            color: #f0e6d2 !important;
            text-decoration: none;
        }

        .btn-secondary {
            background: #6c757d;
            color: white !important;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #5a6268;
            color: white !important;
            text-decoration: none;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 18px;
        }

        .ml-2 {
            margin-left: 10px;
        }

        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .lead {
            font-size: 18px;
            color: #555;
        }

        .print-date {
            color: #999;
            font-size: 14px;
        }

        .table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border: 2px solid #8b4513;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
        }

        .table thead {
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
        }

        .table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0d0b0;
        }

        .table tbody tr:hover {
            background: #faf5eb;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: #f8f1e5;
        }

        .table-bordered {
            border: 2px solid #8b4513;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #8b4513;
        }

        .card {
            background: white;
            border: 2px solid #8b4513;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            font-size: 16px;
        }

        .card-header h4 {
            margin: 0;
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-light {
            background: #e0e0e0;
            color: #333;
            border: 1px solid #999;
        }

        .badge-dark {
            background: #333;
            color: #fff;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #f57c00;
        }

        .alert {
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #f57c00;
            background: #fff3cd;
        }

        .alert-info {
            background: #d1ecf1;
            border-color: #0c5460;
            color: #0c5460;
        }

        .alert h3 {
            margin: 0 0 10px 0;
            color: #0c5460;
        }

        .alert p {
            margin: 0;
            font-size: 16px;
        }

        .mt-4 {
            margin-top: 30px;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .page-break {
            display: none;
        }

        h1, h2, h3, h4 {
            color: #8b4513;
        }

        /* Стили для печати */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .tournament-draw {
                max-width: 100%;
                padding: 0;
            }

            .card {
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }

            .card-header {
                background: #f0f0f0 !important;
                color: #000 !important;
                border-bottom: 2px solid #000 !important;
            }

            .card-header h4 {
                color: #000 !important;
            }

            .table {
                border-collapse: collapse;
            }

            .table th, .table td {
                border: 1px solid #000 !important;
                color: #000 !important;
            }

            .table thead {
                background: #f0f0f0 !important;
                color: #000 !important;
            }

            .badge {
                border: 1px solid #000 !important;
            }

            .badge-light {
                background: #f0f0f0 !important;
                color: #000 !important;
            }

            .badge-dark {
                background: #333 !important;
                color: #fff !important;
            }

            .badge-warning {
                background: #f0f0f0 !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }

            h1, h2, h3, h4 {
                color: #000 !important;
            }

            .page-break {
                display: block;
                page-break-after: always;
            }

            @page {
                margin: 1cm;
                size: A4;
            }
        }
    </style>
@endpush

@section('content')
<div class="tournament-draw" id="printable-area">
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn-chess btn-lg">
            <i class="fas fa-print"></i> Распечатать жеребьевку
        </button>
        <a href="{{ route('tournaments.index') }}" class="btn-secondary btn-lg ml-2">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>

    <div class="text-center mb-4">
        <h1>{{ $tournament->name }}</h1>
        <p class="lead">
            {{ $tournament->location }} |
            Статус: <strong>{{ $tournament->status }}</strong> |
            Туров: {{ $tournament->quantity_rounds }}
        </p>
        <p class="print-date">Дата печати: {{ date('d.m.Y H:i') }}</p>
    </div>

    @if($hasDraw)
        <!-- Турнирная таблица -->
        <h2>Турнирная таблица</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Место</th>
                    <th>Игрок</th>
                    <th>ELO</th>
                    <th>Очки</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $i => $p)
                    <tr>
                        <td><strong>{{ $i + 1 }}</strong></td>
                        <td>{{ $p->username }}</td>
                        <td>{{ $p->elo ?? 1000 }}</td>
                        <td><strong>{{ $points[$p->id] ?? 0 }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Партии по турам -->
        @if(!empty($rounds))
            <h2 class="mt-4">Партии</h2>
            @foreach($rounds as $round => $matches)
                <div class="card mb-3 round-card">
                    <div class="card-header">
                        <h4>Тур {{ $round }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="38%">Белые</th>
                                    <th width="38%">Черные</th>
                                    <th width="19%">Результат</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matches as $i => $match)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            @if($match->winner_id == $match->white_player_id) 👑 @endif
                                            {{ $match->whitePlayer->username ?? '—' }}
                                        </td>
                                        <td>
                                            @if($match->winner_id == $match->black_player_id) 👑 @endif
                                            {{ $match->blackPlayer->username ?? '—' }}
                                        </td>
                                        <td>
                                            @if($match->status == 'played')
                                                <span class="badge 
                                                    @if($match->result == 'white_win') badge-light
                                                    @elseif($match->result == 'black_win') badge-dark
                                                    @else badge-secondary @endif">
                                                    @if($match->result == 'white_win')
                                                        Победа белых
                                                    @elseif($match->result == 'black_win')
                                                        Победа черных
                                                    @elseif($match->result == 'draw')
                                                        Ничья
                                                    @endif
                                                </span>
                                            @else
                                                <span class="badge badge-warning">Ожидает</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="page-break"></div>
            @endforeach
        @endif
    @else
        <!-- Если жеребьевки нет -->
        <div class="alert alert-info">
            <h3>📋 Жеребьевка еще не проводилась</h3>
        </div>
    @endif
</div>
@endsection