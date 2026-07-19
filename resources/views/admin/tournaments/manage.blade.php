@extends('layouts.app')

@section('title', 'Управление: ' . $tournament->name)
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .tournament-manage {
            font-family: 'Georgia', serif;
            background-color: #f8f1e5;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(139, 69, 19, 0.1);
        }

        .tournament-manage h1 {
            color: #8b4513;
            margin-bottom: 30px;
            text-align: center;
        }

        .card {
            background: white;
            border: 2px solid #8b4513;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        .card-header {
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            font-size: 16px;
        }

        .card-body {
            padding: 20px;
        }

        .card-body p {
            margin: 8px 0;
        }

        .btn-success {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin: 5px;
            cursor: pointer;
        }

        .btn-success:hover {
            background: #1b5e20;
            color: white;
            transform: scale(1.02);
        }

        .btn-danger {
            background: #c62828;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin: 5px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #b71c1c;
            color: white;
            transform: scale(1.02);
        }

        .btn-default {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin: 5px;
        }

        .btn-default:hover {
            background: #5a6268;
            color: white;
            transform: scale(1.02);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: not-allowed;
            opacity: 0.6;
            margin: 5px;
        }

        .btn-primary {
            background: #1a5276;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #154360;
            transform: scale(1.02);
        }

        .btn-draw-view {
            background: #f57c00;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
            margin: 5px;
            cursor: pointer;
        }

        .btn-draw-view:hover {
            background: #e65100;
            color: white;
            transform: scale(1.02);
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

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
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

        .table tbody tr:nth-of-type(odd) {
            background: #f8f1e5;
        }

        .label-warning {
            background: #fff3cd;
            color: #856404;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-win {
            background: #2e7d32;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-draw {
            background: #f57c00;
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            background: #faf5eb;
            border-radius: 12px;
            border: 2px solid #8b4513;
            margin: 20px 0;
        }

        .no-data h4 {
            color: #8b4513;
            margin-bottom: 10px;
        }

        .no-data p {
            color: #666;
        }

        .actions-cell {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
        }

        .modal.active {
            display: flex !important;
        }

        .modal-dialog {
            background: white;
            border-radius: 15px;
            border: 3px solid #8b4513;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin: 20px;
            pointer-events: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 2px solid #e0d0b0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #8b4513, #6b3100);
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .modal-header h4 {
            margin: 0;
            font-size: 20px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .modal-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body p {
            margin: 10px 0;
            font-size: 16px;
        }

        .modal-body select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 10px;
            pointer-events: auto;
            background: white;
        }

        .modal-body select:focus {
            outline: none;
            border-color: #8b4513;
        }

        .modal-footer {
            padding: 20px;
            border-top: 2px solid #e0d0b0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-modal-close {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-modal-close:hover {
            background: #5a6268;
        }

        .btn-modal-submit {
            background: #8b4513;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-modal-submit:hover {
            background: #6b3100;
        }

        h3, h4 {
            color: #8b4513;
            margin-top: 30px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .tournament-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .status-запланирован {
            background: #fff3cd;
            color: #856404;
        }

        .status-впроцессе {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-завершен {
            background: #d4edda;
            color: #155724;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-weight: bold;
        }

        .notification-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .notification-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .notification .close-btn {
            float: right;
            background: none;
            border: none;
            color: inherit;
            font-weight: bold;
            font-size: 20px;
            cursor: pointer;
            margin-left: 15px;
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            
            .table {
                font-size: 14px;
            }
            
            .table th, .table td {
                padding: 8px;
            }
            
            .modal-dialog {
                width: 95%;
            }

            .actions-cell {
                flex-direction: column;
            }

            .btn-primary, .btn-success, .btn-danger, .btn-default, .btn-draw-view {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="tournament-manage">
    <h1>Управление: {{ $tournament->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="card">
            <div class="card-header">Статус</div>
            <div class="card-body">
                <p>
                    <span class="tournament-status status-{{ strtolower(str_replace(' ', '', $tournament->status)) }}">
                        {{ $tournament->status }}
                    </span>
                </p>
                <p>Участников: {{ $participants->count() }}</p>
                <p>Тур: {{ $currentRound }} / {{ $tournament->quantity_rounds }}</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Статистика</div>
            <div class="card-body">
                <p>Партий: {{ $hasDraw ? array_sum(array_map('count', $rounds)) : 0 }}</p>
                <p>Сыграно: {{ $hasDraw ? array_sum(array_map('count', $rounds)) - $pendingMatches : 0 }}</p>
                <p>Ожидают: {{ $pendingMatches }}</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Действия</div>
            <div class="card-body">
                @if($participants->isEmpty())
                    <div class="alert alert-warning" style="margin-bottom:10px;">
                        ⚠️ На турнире нет участников. Жеребьевка невозможна.
                    </div>
                @endif

                @if(!$hasDraw && $tournament->status == 'Запланирован')
                    @if($participants->isNotEmpty())
                        <form action="{{ route('admin.tournaments.draw', $tournament->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-success" onclick="return confirm('Провести жеребьевку первого тура?')">
                                Провести жеребьевку (1 тур)
                            </button>
                        </form>
                    @else
                        <button class="btn-secondary" disabled>Нет участников</button>
                    @endif
                @elseif($tournament->status == 'В процессе')
                    @if($currentRound < $tournament->quantity_rounds && $pendingMatches == 0)
                        @if($participants->isNotEmpty())
                            <form action="{{ route('admin.tournaments.draw', $tournament->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-success" onclick="return confirm('Провести жеребьевку {{ $currentRound + 1 }}-го тура?')">
                                    Провести жеребьевку ({{ $currentRound + 1 }} тур)
                                </button>
                            </form>
                        @else
                            <button class="btn-secondary" disabled>Нет участников</button>
                        @endif
                    @elseif($pendingMatches > 0)
                        <button class="btn-secondary" disabled>Дождитесь окончания тура</button>
                    @endif

                    @if($hasDraw)
                        <form action="{{ route('admin.tournaments.reset-draw', $tournament->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Удалить все партии?')">
                                Сбросить жеребьевку
                            </button>
                        </form>
                    @endif
                @endif

                @if($hasDraw)
                    <a href="{{ route('admin.tournaments.draw-view', $tournament->id) }}" class="btn-draw-view" target="_blank">
                        📋 Просмотр жеребьевки
                    </a>
                @endif

                <a href="{{ route('admin.tournaments.show', $tournament->id) }}" class="btn-default">Назад</a>
            </div>
        </div>
    </div>

    <!-- Турнирная таблица -->
    <h3>Турнирная таблица</h3>
    @if($participants->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Место</th>
                    <th>Игрок</th>
                    <th>ELO</th>
                    <th>Очки</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $i => $p)
                    <tr>
                        <td><strong>{{ $i + 1 }}</strong></td>
                        <td>{{ $p->username }}</td>
                        <td>{{ $p->elo ?? 1200 }}</td>
                        <td><strong>{{ $points[$p->id] ?? 0 }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h4>Нет участников</h4>
            <p>На этот турнир еще никто не зарегистрировался</p>
        </div>
    @endif

    <!-- Партии -->
    @if($hasDraw)
        <h3>Партии</h3>
        @if(!empty($rounds))
            @foreach($rounds as $round => $matches)
                <h4>Тур {{ $round }}</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Белые</th>
                            <th width="25%">Черные</th>
                            <th width="20%">Результат</th>
                            <th width="25%">Действие</th>
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
                                        @if($match->result == 'white_win')
                                            <span class="badge-win">Победа белых</span>
                                        @elseif($match->result == 'black_win')
                                            <span class="badge-win">Победа черных</span>
                                        @elseif($match->result == 'draw')
                                            <span class="badge-draw">Ничья</span>
                                        @endif
                                    @else
                                        <span class="badge-pending">Ожидает</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        @if($match->status != 'played')
                                            <button class="btn-primary" onclick="showResultModal({{ $match->id }}, '{{ $match->whitePlayer->username ?? '—' }}', '{{ $match->blackPlayer->username ?? '—' }}')">
                                                Ввести результат
                                            </button>
                                        @else
                                            <span style="color:#666;">✓ Завершена</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <div class="no-data">
                <h4>Нет партий</h4>
                <p>Партии еще не созданы</p>
            </div>
        @endif
    @endif
</div>

<!-- Модальное окно -->
<div class="modal" id="resultModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h4>Результат партии</h4>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form method="POST" id="resultForm" action="{{ route('admin.tournaments.update-match') }}">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="match_id" id="matchId">
                <p><strong>Белые:</strong> <span id="whitePlayer"></span></p>
                <p><strong>Черные:</strong> <span id="blackPlayer"></span></p>
                <select name="result" id="resultSelect" required>
                    <option value="">Выберите результат</option>
                    <option value="white_win">Победа белых</option>
                    <option value="black_win">Победа черных</option>
                    <option value="draw">Ничья</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-modal-close" onclick="closeModal()">Отмена</button>
                <button type="submit" class="btn-modal-submit" id="submitResult">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showResultModal(id, white, black) {
        document.getElementById('matchId').value = id;
        document.getElementById('whitePlayer').textContent = white;
        document.getElementById('blackPlayer').textContent = black;
        document.getElementById('resultSelect').selectedIndex = 0;
        document.getElementById('resultModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('resultModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('resultSelect').selectedIndex = 0;
    }

    // Закрытие при клике на фон
    document.getElementById('resultModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Предотвращаем закрытие при клике внутри модального окна
    document.querySelector('.modal-dialog').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('resultModal');
            if (modal.classList.contains('active')) {
                closeModal();
            }
        }
    });

    // Отправка формы через AJAX
    document.getElementById('resultForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const result = document.getElementById('resultSelect').value;
        
        if (!result) {
            showNotification('error', 'Пожалуйста, выберите результат');
            return;
        }
        
        const submitBtn = document.getElementById('submitResult');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = '⏳ Сохранение...';
        submitBtn.disabled = true;
        
        fetch('{{ route("admin.tournaments.update-match") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                closeModal();
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Ошибка при сохранении результата');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            showNotification('error', 'Ошибка при отправке запроса');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    function showNotification(type, message) {
        // Удаляем старые уведомления
        const oldNotifications = document.querySelectorAll('.notification');
        oldNotifications.forEach(el => el.remove());
        
        const alertClass = type === 'success' ? 'notification-success' : 'notification-error';
        const html = `
            <div class="notification ${alertClass}">
                ${message}
                <button class="close-btn" onclick="this.parentElement.remove()">×</button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', html);
        
        setTimeout(() => {
            const notification = document.querySelector('.notification');
            if (notification) notification.remove();
        }, 5000);
    }
</script>
@endsection