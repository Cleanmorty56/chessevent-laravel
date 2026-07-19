<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Основные теги --}}
    @php
        try {
            $seo = seo();
        } catch (\Exception $e) {
            $seo = [
                'title' => env('SEO_TITLE', 'ChessEvent'),
                'description' => env('SEO_DESCRIPTION', 'Шахматный портал'),
                'keywords' => env('SEO_KEYWORDS', 'шахматы, турниры'),
                'image' => asset('img/og-image.jpg'),
                'url' => url()->current(),
                'author' => 'ChessEvent',
                'robots' => 'index, follow',
            ];
        }
    @endphp
    
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <meta name="author" content="{{ $seo['author'] }}">
    
    {{-- Open Graph (для соцсетей) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['url'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="ChessEvent">
    <meta property="og:locale" content="ru_RU">
    
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $seo['url'] }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css">
    
    <style>
        .navbar-brand {
            width: 256px;
            display: flex;
            justify-content: center;
        }
        .social-icons {
            display: flex;
            width: 204px;
            gap: 10px;
        }
        .navbar-nav .nav-link {
            color: #f0e6d2 !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ffd700 !important;
        }
        .btn-logout {
            background: none;
            border: none;
            color: #f0e6d2 !important;
            cursor: pointer;
        }
        .btn-logout:hover {
            color: #ffd700 !important;
        }
        .footer-copyright {
            color: #f0e6d2;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ШАПКА -->
    <header id="header">
        <nav class="navbar navbar-expand-md navbar-dark" style="background-color: #6b5534 !important; height: 85px !important;">
            <div class="container">
                <!-- Логотип -->
                <a class="navbar-brand" href="{{ route('home') }}" style="width: 256px; display: flex; justify-content: center;">
                    <img src="{{ asset('img/logo.png') }}" alt="ChessEvent" style="width: 100px;">
                </a>

                <!-- Меню -->
                <div class="navbar-nav mx-auto">
                    <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    <a class="nav-link" href="{{ route('tournaments.index') }}">Турниры</a>
                    <a class="nav-link" href="{{ route('about') }}">О нас</a>
                    <a class="nav-link" href="{{ route('game.quick') }}">Шахматы онлайн</a>

                    @auth
                        @if(auth()->user()->role == 1)
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Админ-панель</a>
                        @else
                            <a class="nav-link" href="{{ route('profile') }}">Профиль</a>
                        @endif
                    @else
                        <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                    @endauth
                </div>

                <!-- Правая часть -->
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            <button type="submit" class="btn-logout nav-link">Выход</button>
                        </form>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">Вход</a>
                    @endauth

                    <!-- Соцсети -->
                    <div class="social-icons">
                        <a href="https://vk.com/club240232906" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-vk-2504953.png') }}" alt="VK" style="width: 24px; height:24px;">
                        </a>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-telegram-2111646.png') }}" alt="Telegram" style="width: 24px; height:24px;">
                        </a>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-odnoklassniki-2504930.png') }}" alt="OK" style="width: 24px; height:24px;">
                        </a>
                    </div>
                </div>

                <!-- Кнопка мобильного меню -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </header>

    <!-- ОСНОВНОЙ КОНТЕНТ -->
    <main id="main" role="main">
        <div class="container" style="padding: 0 !important;">
            @yield('content')
        </div>
    </main>

    <!-- ФУТЕР -->
    <footer id="footer" class="py-4" style="background-color: #6b5534;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start">
                    <img src="{{ asset('img/logo.png') }}" alt="ChessEvent" style="width: 128px; height: 128px;">
                </div>
                <div class="col-md-4">
                    <nav class="footer-nav d-flex justify-content-center flex-wrap">
                        <a class="nav-link mx-2 text-white" href="{{ route('home') }}">Главная</a>
                        <a class="nav-link mx-2 text-white" href="{{ route('tournaments.index') }}">Турниры</a>
                        <a class="nav-link mx-2 text-white" href="{{ route('about') }}">О нас</a>
                        @auth
                            @if(auth()->user()->role == 1)
                                <a class="nav-link mx-2 text-white" href="{{ route('admin.dashboard') }}">Админ-панель</a>
                            @else
                                <a class="nav-link mx-2 text-white" href="{{ route('profile') }}">Профиль</a>
                            @endif
                        @else
                            <a class="nav-link mx-2 text-white" href="{{ route('register') }}">Регистрация</a>
                        @endauth
                    </nav>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="social-icons">
                        <a href="https://vk.com/club240232906" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-vk-2504953.png') }}" alt="VK" style="width: 24px; height:24px;">
                        </a>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-telegram-2111646.png') }}" alt="Telegram" style="width: 24px; height:24px;">
                        </a>
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('img/free-icon-odnoklassniki-2504930.png') }}" alt="OK" style="width: 24px; height:24px;">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="footer-copyright text-white">
                        &copy; ChessEvent {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>