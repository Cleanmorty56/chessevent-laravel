<?php

use Illuminate\Support\Facades\Route;
use App\Models\Gamemode;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\TournamentController as AdminTournamentController;
use App\Http\Controllers\Admin\PlanningController as AdminPlanningController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    $gamemodes = Gamemode::pluck('name', 'id')->toArray();
    return view('static.home', compact('gamemodes'));
})->name('home');

Route::post('/planning', [PlanningController::class, 'store'])
    ->middleware('auth')
    ->name('planning.store');

Route::get('/about', function () {
    return view('static.about');
})->name('about');

// Публичные маршруты для турниров
Route::get('/tournaments/{id?}', [TournamentController::class, 'index'])->name('tournaments.index');
Route::post('/tournaments/register/{id}', [TournamentController::class, 'register'])->name('tournaments.register');
Route::post('/tournaments/unregister/{id}', [TournamentController::class, 'unregister'])->name('tournaments.unregister');
Route::get('/tournaments/draw/{id}', [TournamentController::class, 'draw'])->name('tournaments.draw');

// ========== АДМИНКА ==========
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Главная админки
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // ===== Управление турнирами =====
    Route::get('/tournaments', [AdminTournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [AdminTournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [AdminTournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [AdminTournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [AdminTournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [AdminTournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [AdminTournamentController::class, 'destroy'])->name('tournaments.destroy');
    
    // Управление турниром (жеребьевка, результаты)
    Route::get('/tournaments/{tournament}/manage', [AdminTournamentController::class, 'manage'])->name('tournaments.manage');
    Route::post('/tournaments/{tournament}/draw', [AdminTournamentController::class, 'draw'])->name('tournaments.draw');
    Route::delete('/tournaments/{tournament}/reset-draw', [AdminTournamentController::class, 'resetDraw'])->name('tournaments.reset-draw');
    Route::post('/tournaments/update-match', [AdminTournamentController::class, 'updateMatch'])->name('tournaments.update-match');
    
    // Просмотр жеребьевки в админке
    Route::get('/tournaments/{tournament}/draw-view', [AdminTournamentController::class, 'drawView'])->name('tournaments.draw-view');
    
    // ===== Управление заявками =====
    Route::get('/planning', [AdminPlanningController::class, 'index'])->name('planning.index');
    Route::get('/planning/{planning}', [AdminPlanningController::class, 'show'])->name('planning.show');
    Route::post('/planning/{planning}/approve', [AdminPlanningController::class, 'approve'])->name('planning.approve');
    Route::post('/planning/{planning}/reject', [AdminPlanningController::class, 'reject'])->name('planning.reject');
    Route::delete('/planning/{planning}', [AdminPlanningController::class, 'destroy'])->name('planning.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========== ПРОФИЛЬ ==========
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/download-pgn/{gameId}', [ProfileController::class, 'downloadPgn'])->name('profile.download-pgn');
});

// ========== Онлайн-партия ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/game', [GameController::class, 'quick'])->name('game.quick');
});

// Страница сброса пароля
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

// Обработка сброса пароля
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// sitemap.xml
Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')
        ->header('Content-Type', 'text/xml');
});

require __DIR__.'/auth.php';