@extends('layouts.app')

@section('title', 'Заявки на планирование турнира')
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
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(139, 69, 19, 0.1);
            margin: 20px auto;
            max-width: 1400px;
        }

        .chess-main-title {
            color: #8b4513;
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            padding-bottom: 10px;
            border-bottom: 2px solid #8b4513;
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

        .planning-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
        }

        .planning-card {
            background: white;
            border: 2px solid #8b4513;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .planning-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.3);
        }

        .card-approved { border-left: 8px solid #2e7d32; }
        .card-rejected { border-left: 8px solid #c62828; }
        .card-pending { border-left: 8px solid #f57c00; }

        .planning-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: linear-gradient(135deg, #8b4513, #6b3100);
            color: white;
        }

        .planning-id {
            font-size: 16px;
            font-weight: bold;
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-approved { background: #2e7d32; color: white; }
        .status-rejected { background: #c62828; color: white; }
        .status-pending { background: #f57c00; color: white; }

        .planning-card-body {
            padding: 18px;
            background: #faf5eb;
        }

        .planning-field {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px dashed #e0d0b0;
        }

        .planning-field:last-child {
            border-bottom: none;
        }

        .field-label {
            font-weight: bold;
            color: #8b4513;
            font-size: 13px;
            min-width: 120px;
        }

        .field-value {
            color: #555;
            font-size: 13px;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        .planning-card-footer {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 15px;
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
            font-size: 13px;
            transition: all 0.3s;
        }

        .btn-card-view:hover {
            background: #3e2723;
            color: white;
            text-decoration: none;
            transform: scale(1.02);
        }

        .btn-card-approve {
            flex: 1;
            background: #2e7d32;
            color: white;
            text-decoration: none;
            padding: 8px;
            text-align: center;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-card-approve:hover {
            background: #1b5e20;
            color: white;
            transform: scale(1.02);
        }

        .btn-card-reject {
            flex: 1;
            background: #f57c00;
            color: white;
            text-decoration: none;
            padding: 8px;
            text-align: center;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-card-reject:hover {
            background: #e65100;
            color: white;
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
            font-size: 13px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-card-delete:hover {
            background: #b71c1c;
            color: white;
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .chess-theme { padding: 15px; }
            .planning-cards { grid-template-columns: 1fr; gap: 20px; }
            .planning-card-header { padding: 12px 15px; }
            .planning-card-body { padding: 12px; }
            .planning-field { flex-direction: column; align-items: flex-start; gap: 5px; }
            .field-label { min-width: auto; }
            .field-value { text-align: left; max-width: 100%; }
            .planning-card-footer { flex-wrap: wrap; }
            .btn-card-view, .btn-card-approve, .btn-card-reject, .btn-card-delete {
                flex: 1 1 calc(50% - 8px);
                font-size: 12px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .chess-theme { padding: 10px; }
            .chess-main-title { font-size: 20px; }
            .planning-id { font-size: 12px; }
            .status-badge { font-size: 10px; padding: 3px 10px; }
            .field-label, .field-value { font-size: 11px; }
            .btn-card-view, .btn-card-approve, .btn-card-reject, .btn-card-delete {
                flex: 1 1 100%;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
<div class="chess-theme">
    <h1 class="chess-main-title">Заявки на планирование турнира</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="planning-cards">
        @forelse($plannings as $model)
            <div class="planning-card 
                {{ $model->status == 'approved' ? 'card-approved' : ($model->status == 'rejected' ? 'card-rejected' : 'card-pending') }}">
                
                <div class="planning-card-header">
                    <div class="planning-id">Заявка #{{ $model->id }}</div>
                    <div class="planning-status">
                        @php
                            $statusText = [
                                'pending' => 'На рассмотрении',
                                'approved' => 'Одобрено',
                                'rejected' => 'Отклонено'
                            ][$model->status] ?? $model->status;
                            
                            $statusClass = $model->status == 'approved' ? 'status-approved' : 
                                          ($model->status == 'rejected' ? 'status-rejected' : 'status-pending');
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
                
                <div class="planning-card-body">
                    <div class="planning-field">
                        <span class="field-label">📋 Содержание:</span>
                        <span class="field-value">{{ $model->content }}</span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">👤 Организатор:</span>
                        <span class="field-value">{{ $model->organizer }}</span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">👨‍💻 Пользователь:</span>
                        <span class="field-value">{{ $model->user->username ?? '—' }}</span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">🎮 Режим игры:</span>
                        <span class="field-value">{{ $model->gamemode->name ?? '—' }}</span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">🔄 Количество туров:</span>
                        <span class="field-value">{{ $model->quantity_rounds }}</span>
                    </div>
                    
                    <div class="planning-field">
                        <span class="field-label">📅 Дата создания:</span>
                        <span class="field-value">{{ $model->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>
                
                <div class="planning-card-footer">
                    <a href="{{ route('admin.planning.show', $model->id) }}" class="btn-card-view">👁️ Просмотр</a>
                    
                    @if($model->status != 'approved')
                        <form action="{{ route('admin.planning.approve', $model->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-card-approve" onclick="return confirm('Вы уверены, что хотите одобрить эту заявку?')">
                                ✅ Одобрить
                            </button>
                        </form>
                    @endif
                    
                    @if($model->status != 'rejected')
                        <form action="{{ route('admin.planning.reject', $model->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-card-reject" onclick="return confirm('Вы уверены, что хотите отклонить эту заявку?')">
                                ❌ Отклонить
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.planning.destroy', $model->id) }}" method="POST" style="flex:1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-card-delete" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">
                            🗑️ Удалить
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:40px;background:#faf5eb;border-radius:12px;border:2px solid #8b4513;">
                <h3 style="color:#8b4513;">Заявки не найдены</h3>
                <p style="color:#666;">Нет заявок на планирование турниров</p>
            </div>
        @endforelse
    </div>
</div>
@endsection