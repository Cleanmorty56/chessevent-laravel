@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
<form method="POST" action="{{ route('profile.update-password') }}">
    @csrf

    <div class="form-group">
        <label for="oldPassword">Текущий пароль</label>
        <input type="password" 
               id="oldPassword" 
               name="oldPassword" 
               class="form-control @error('oldPassword') is-invalid @enderror" 
               placeholder="Введите текущий пароль" 
               required>
        @error('oldPassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="newPassword">Новый пароль</label>
        <input type="password" 
               id="newPassword" 
               name="newPassword" 
               class="form-control @error('newPassword') is-invalid @enderror" 
               placeholder="Введите новый пароль (минимум 8 символов)" 
               required>
        @error('newPassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="newPassword_confirmation">Подтверждение пароля</label>
        <input type="password" 
               id="newPassword_confirmation" 
               name="newPassword_confirmation" 
               class="form-control @error('newPassword_confirmation') is-invalid @enderror" 
               placeholder="Подтвердите новый пароль" 
               required>
        @error('newPassword_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i> Изменить пароль
    </button>
</form>