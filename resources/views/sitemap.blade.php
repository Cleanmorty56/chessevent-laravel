@php
    header('Content-Type: application/xml; charset=utf-8');
    $url = url('/');
    $tournamentsIndex = route('tournaments.index');
    $about = route('about');
    $game = route('game.quick');
    $now = date('Y-m-d');
    $tournaments = \App\Models\Tournament::all();
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ $url }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ $tournamentsIndex }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ $about }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ $game }}</loc>
        <lastmod>{{ $now }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($tournaments as $tournament)
    <url>
        <loc>{{ route('tournaments.draw', $tournament->id) }}</loc>
        <lastmod>{{ $tournament->updated_at ? $tournament->updated_at->format('Y-m-d') : $now }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>