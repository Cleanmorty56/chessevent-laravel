<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Level;
use App\Models\TournamentMatch;
use App\Models\TournamentBye;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $levels = Level::all();
        
        $query = Tournament::with(['level', 'gamemode']);

        if ($id !== null && $id !== 'all') {
            $query->where('level_id', $id);
            $selectedLevel = Level::find($id);
            $selectedLevelId = $id;
        } else {
            $selectedLevel = null;
            $selectedLevelId = null;
        }

        $tournaments = $query->get();
        $userTournamentIds = [];

        if (auth()->check()) {
            // Используем прямой запрос вместо связи с withTimestamps()
            $userTournamentIds = \DB::table('reg_to_tournaments')
                ->where('user_id', auth()->id())
                ->pluck('tournament_id')
                ->toArray();
        }

        return view('tournaments.index', [
            'levels' => $levels,
            'tournaments' => $tournaments,
            'userTournamentIds' => $userTournamentIds,
            'selectedLevel' => $selectedLevel,
            'selectedLevelId' => $selectedLevelId,
        ]);
    }

    public function register($id)
    {
        $tournament = Tournament::findOrFail($id);
        $user = auth()->user();

        // Проверяем, зарегистрирован ли уже пользователь
        $exists = \DB::table('reg_to_tournaments')
            ->where('tournament_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Вы уже зарегистрированы на этот турнир.');
        }

        // Регистрируем пользователя
        \DB::table('reg_to_tournaments')->insert([
            'tournament_id' => $id,
            'user_id' => $user->id,
            'registration_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Вы успешно зарегистрированы на турнир!');
    }

    public function unregister($id)
    {
        $tournament = Tournament::findOrFail($id);
        $user = auth()->user();

        // Проверяем, зарегистрирован ли пользователь
        $exists = \DB::table('reg_to_tournaments')
            ->where('tournament_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'Вы не зарегистрированы на этот турнир.');
        }

        // Отменяем регистрацию
        \DB::table('reg_to_tournaments')
            ->where('tournament_id', $id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'Регистрация отменена.');
    }

    public function draw($id)
    {
        $tournament = Tournament::with(['level', 'gamemode'])->findOrFail($id);
        
        // Получаем всех участников (регистрации)
        $registrations = $tournament->users()->get();
        
        // Проверяем, есть ли жеребьевка
        $hasDraw = TournamentMatch::where('tournament_id', $tournament->id)->exists();
        
        // Получаем партии по турам
        $rounds = [];
        $points = [];
        
        if ($hasDraw) {
            // Получаем максимальный тур
            $maxRound = TournamentMatch::where('tournament_id', $tournament->id)->max('round') ?? 0;
            
            // Группируем партии по турам
            for ($round = 1; $round <= $maxRound; $round++) {
                $matches = TournamentMatch::where('tournament_id', $tournament->id)
                    ->where('round', $round)
                    ->with(['whitePlayer', 'blackPlayer'])
                    ->get();
                
                $rounds[$round] = $matches;
            }
            
            // Считаем очки для каждого участника
            foreach ($registrations as $p) {
                $userId = $p->id;
                
                // Очки из сыгранных партий
                $matches = TournamentMatch::where('tournament_id', $tournament->id)
                    ->where(function ($query) use ($userId) {
                        $query->where('white_player_id', $userId)
                              ->orWhere('black_player_id', $userId);
                    })
                    ->where('status', 'played')
                    ->get();
                
                $pts = 0;
                foreach ($matches as $m) {
                    if ($m->winner_id == $userId) {
                        $pts += 1;
                    } elseif ($m->result == 'draw') {
                        $pts += 0.5;
                    }
                }
                
                // Очки за пропуски
                $byePts = TournamentBye::where('tournament_id', $tournament->id)
                    ->where('user_id', $userId)
                    ->sum('points') ?? 0;
                
                $points[$userId] = $pts + $byePts;
            }
            
            // Сортируем участников по очкам
            $registrations = $registrations->sortByDesc(function($user) use ($points) {
                return $points[$user->id] ?? 0;
            })->values();
        }
        
        return view('tournaments.draw', compact(
            'tournament',
            'registrations',
            'hasDraw',
            'rounds',
            'points'
        ));
    }
}