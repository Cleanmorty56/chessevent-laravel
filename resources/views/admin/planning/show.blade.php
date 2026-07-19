@extends('layouts.app')

@section('title', 'Просмотр заявки')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <style>
        .planning-detail {
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

        .status-badge {
            display: inline-block;
            padding: 4px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-approved { background: #2e7d32; color: white; }
        .status-rejected { background: #c62828; color: white; }
        .status-pending { background: #f57c00; color: white; }

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
            margin-top: 20px;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
            text-decoration: none;
        }

        .detail-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .planning-detail { padding: 20px; }
            .detail-field { flex-direction: column; align-items: flex-start; gap: 5px; }
            .detail-actions { flex-direction: column; }
            .btn-back { width: 100%; text-align: center; }
        }
    </style>
@endpush

@section('content')
<div class="planning-detail">
    <div class="detail-header">
        <h1 style="color:#8b4513;">Заявка #{{ $planning->id }}</h1>
        <div style="margin-top:10px;">
            @php
                $statusText = [
                    'pending' => 'На рассмотрении',
                    'approved' => 'Одобрено',
                    'rejected' => 'Отклонено'
                ][$planning->status] ?? $planning->status;
                
                $statusClass = $planning->status == 'approved' ? 'status-approved' : 
                              ($planning->status == 'rejected' ? 'status-rejected' : 'status-pending');
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
        </div>
    </div>

    <div class="detail-field">
        <span class="detail-label">📋 Содержание:</span>
        <span class="detail-value">{{ $planning->content }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">👤 Организатор:</span>
        <span class="detail-value">{{ $planning->organizer }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">👨‍💻 Пользователь:</span>
        <span class="detail-value">{{ $planning->user->username ?? '—' }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">🎮 Режим игры:</span>
        <span class="detail-value">{{ $planning->gamemode->name ?? '—' }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">🔄 Количество туров:</span>
        <span class="detail-value">{{ $planning->quantity_rounds }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">📅 Дата создания:</span>
        <span class="detail-value">{{ $planning->created_at->format('d.m.Y H:i') }}</span>
    </div>

    <div class="detail-field">
        <span class="detail-label">📅 Дата обновления:</span>
        <span class="detail-value">{{ $planning->updated_at->format('d.m.Y H:i') }}</span>
    </div>

    <div class="detail-actions">
        @if($planning->status != 'approved')
            <form action="{{ route('admin.planning.approve', $planning->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-card-approve" style="background:#2e7d32;color:white;padding:10px 25px;border:none;border-radius:8px;font-size:16px;cursor:pointer;" onclick="return confirm('Вы уверены, что хотите одобрить эту заявку?')">
                    ✅ Одобрить
                </button>
            </form>
        @endif
        
        @if($planning->status != 'rejected')
            <form action="{{ route('admin.planning.reject', $planning->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-card-reject" style="background:#f57c00;color:white;padding:10px 25px;border:none;border-radius:8px;font-size:16px;cursor:pointer;" onclick="return confirm('Вы уверены, что хотите отклонить эту заявку?')">
                    ❌ Отклонить
                </button>
            </form>
        @endif
        
        <a href="{{ route('admin.planning.index') }}" class="btn-back">⬅ Назад к списку</a>
    </div>
</div>
@endsection