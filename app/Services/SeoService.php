<?php

namespace App\Services;

class SeoService
{
    protected $defaultTitle;
    protected $defaultDescription;
    protected $defaultKeywords;
    protected $defaultImage;

    public function __construct()
    {
        $this->defaultTitle = env('SEO_TITLE', 'ChessEvent');
        $this->defaultDescription = env('SEO_DESCRIPTION', 'Шахматный портал');
        $this->defaultKeywords = env('SEO_KEYWORDS', 'шахматы, турниры');
        $this->defaultImage = asset('img/og-image.jpg');
    }

    public function generate(array $data = []): array
    {
        return [
            'title' => $data['title'] ?? $this->defaultTitle,
            'description' => $data['description'] ?? $this->defaultDescription,
            'keywords' => $data['keywords'] ?? $this->defaultKeywords,
            'image' => $data['image'] ?? $this->defaultImage,
            'url' => $data['url'] ?? url()->current(),
            'author' => $data['author'] ?? 'ChessEvent',
            'robots' => $data['robots'] ?? 'index, follow',
        ];
    }
}