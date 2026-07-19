@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
<div class="delete-account-section">
    <h3><i class="fas fa-exclamation-triangle" style="color:#c62828;"></i> Удаление аккаунта</h3>
    <p class="delete-warning">
        Внимание! Удаление аккаунта приведет к безвозвратной потере всех данных.
    </p>

    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Вы уверены, что хотите удалить свой аккаунт? Это действие необратимо!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete">
            <i class="fas fa-trash"></i> Удалить аккаунт
        </button>
    </form>
</div>

@push('styles')
    <style>
        .delete-account-section {
            padding: 20px;
            background: #fff5f5;
            border: 2px solid #c62828;
            border-radius: 12px;
        }

        .delete-account-section h3 {
            color: #c62828;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .delete-warning {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .btn-delete {
            background: #c62828;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-delete:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.3);
        }

        .btn-delete i {
            margin-right: 8px;
        }
    </style>
@endpush