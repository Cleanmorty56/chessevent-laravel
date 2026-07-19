<?php

use App\Services\SeoService;

if (!function_exists('seo')) {
    function seo($data = [])
    {
        try {
            $service = app(SeoService::class);
            return $service->generate($data);
        } catch (\Exception $e) {
            // Возвращаем базовые значения в случае ошибки
            return [
                'title' => env('SEO_TITLE', 'ChessEvent'),
                'description' => env('SEO_DESCRIPTION', 'Шахматный портал'),
                'keywords' => env('SEO_KEYWORDS', 'шахматы, турниры'),
                'image' => asset('img/og-image.jpg'),
                'url' => url()->current(),
                'author' => 'ChessEvent',
                'robots' => 'index, follow',
            ];
        }
    }
}