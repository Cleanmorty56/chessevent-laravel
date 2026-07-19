@extends('layouts.app')

@section('title', 'Главная')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        /* Дополнительные стили, если нужно */
        .bannernepo {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            padding: 80px 0;
            text-align: center;
            color: white;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .titlenepo {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
        }
        .banner-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .containerspeaker {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        .quote-box {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            background: #f8f1e5;
            border-left: 5px solid #8b4513;
            border-radius: 10px;
        }
        .quote-box p {
            font-size: 22px;
            font-style: italic;
            color: #4a2200;
            font-family: 'Georgia', serif;
            line-height: 1.6;
            margin: 0;
        }
        .author-image-container {
            text-align: center;
            flex: 0 0 200px;
        }
        .author-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #8b4513;
        }
        .author-name {
            margin-top: 10px;
            font-weight: bold;
            color: #8b4513;
            font-size: 18px;
        }
        .block_kon {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .form {
            background: #faf5eb;
            border: 2px solid #8b4513;
            border-radius: 15px;
            padding: 30px;
        }
        .form .title h2 {
            color: #8b4513;
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
        }
        .form-content .info {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            background: #fff9f0;
            font-family: 'Georgia', serif;
            min-height: 100px;
        }
        .form-content .info2 {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            background: #fff9f0;
            font-family: 'Georgia', serif;
        }
        .mal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }
        .mal .info1 {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            background: #fff9f0;
            font-family: 'Georgia', serif;
        }
        .mal select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0d0b0;
            border-radius: 8px;
            background: #fff9f0;
            font-family: 'Georgia', serif;
        }
        .btnzajavka {
            text-align: center;
            margin-top: 20px;
        }
        .btn-top {
            background: #8b4513;
            color: #f0e6d2;
            padding: 12px 40px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-top:hover {
            background: #6b3100;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        @media (max-width: 768px) {
            .titlenepo { font-size: 32px; }
            .containerspeaker { flex-direction: column; text-align: center; }
            .mal { grid-template-columns: 1fr; }
            .form { padding: 20px; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="bannernepo" style="background-image: url('{{ asset('img/nepo.jpeg') }}');     background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 600px;
        width: calc(100% - 40px);
        max-width: calc(100vw - 40px);
        margin: 40px auto 80px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
            <div class="banner-content">
                <h1 class="titlenepo">Попробуй свои силы!</h1>
                <a href="{{ route('tournaments.index') }}" class="btn btn-primary btn-lg"
                   style="background-color: #646560; border: none; padding: 12px 40px; font-size: 1.2rem; font-weight: 600;">
                    Принять участие!
                </a>
            </div>
        </div>
    </div>

    <div class="containerspeaker">
        <div class="quote-box">
            <p>«Вы можете узнать гораздо больше из проигранной игры, чем от выигранной. Вы должны проиграть сотни игр,
                прежде чем стать хорошим игроком»</p>
        </div>
        <div class="author-image-container">
            <img src="{{ asset('img/e4d76254bf6553bb9eec73d08600f50f.png') }}" alt="Хосе Рауль Капабланка" class="author-image">
            <p class="author-name">Хосе Рауль Капабланка</p>
        </div>
    </div>

    <div class="block_kon">
        <div class="form">
            <div class="title">
                <h2>Заявка на планирование турнира</h2>
            </div>
            <form method="POST" action="{{ route('planning.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-content">
                    <textarea name="content" class="info" placeholder="Содержание заявки" rows="4">{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <input type="text" name="organizer" class="info2" placeholder="Организатор" value="{{ old('organizer') }}" style="margin-top: 15px;">
                    @error('organizer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="mal" style="margin-top: 15px;">
                        <div>
                            <select name="gamemode_id">
                                <option value="">Выберите игровой режим</option>
                                @foreach($gamemodes as $id => $name)
                                    <option value="{{ $id }}" {{ old('gamemode_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('gamemode_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <input type="number" name="quantity_rounds" class="info1" placeholder="Количество туров" value="{{ old('quantity_rounds') }}">
                            @error('quantity_rounds')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <input type="file" name="imageFile" class="info1">
                            @error('imageFile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="btnzajavka">
                    <button type="submit" class="btn-top">Отправить заявку</button>
                </div>
            </form>
        </div>
    </div>
@endsection