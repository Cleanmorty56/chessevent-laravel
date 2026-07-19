@extends('layouts.app')
@push('seo')
    <x-seo 
        title="Шахматный портал ChessEvent - Турниры, рейтинг, онлайн игра"
        description="Играйте в шахматы онлайн, участвуйте в турнирах, повышайте рейтинг ELO."
        keywords="шахматы, турниры, онлайн шахматы, рейтинг ELO"
    />
@endpush
@section('title', 'О нас')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/onas.css') }}">
@endpush

@section('content')
<div class="about-page">
    <div class="chess-header">
        <h1>О нас</h1>
        <h5>Страсть к игре, совершенствование мастерства, сообщество единомышленников</h5>
    </div>

    <div class="about-container">
        <section class="mission-section">
            <h2>Наша миссия</h2>
            <p style="font-size: large">Мы создали эту организацию в 2024 году с целью объединить любителей шахмат всех
                уровней. Наша миссия —
                популяризация шахмат как интеллектуального спорта, развитие стратегического мышления и создание
                дружелюбного сообщества.</p>
        </section>

        <section class="team-section">
            <h2>Наша команда</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="{{ asset('img/vladimir.jpg') }}" alt="Владимир Петров" class="member-photo">
                    <div class="member-info">
                        <h3>Владимир Петров</h3>
                        <p>Главный тренер, гроссмейстер</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="{{ asset('img/ww.jpg') }}" alt="Дмитрий Лакаев" class="member-photo">
                    <div class="member-info">
                        <h3>Дмитрий Лакаев</h3>
                        <p>Организатор турниров</p>
                    </div>
                </div>
                <div class="team-member">
                    <img src="{{ asset('img/tactic.jpg') }}" alt="Иван Иванов" class="member-photo">
                    <div class="member-info">
                        <h3>Иван Иванов</h3>
                        <p>Тренер по тактике</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="join-section">
            <h2>Присоединяйтесь к нам</h2>
            <p style="font-size: large">Мы всегда рады новым участникам! Независимо от вашего уровня, у нас найдется
                место для вас. Участвуйте в турнирах, посещайте мастер-классы или просто приходите сыграть партию.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-chess">Зарегистрироваться</a>
            @endguest
        </section>
    </div>
</div>
@endsection