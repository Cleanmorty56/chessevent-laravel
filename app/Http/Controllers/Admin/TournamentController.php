<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Level;
use App\Models\Gamemode;
use App\Models\TournamentMatch;
use App\Models\TournamentBye;
use App\Models\User;
use App\Services\VKService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Доступ запрещён');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $tournaments = Tournament::with(['level', 'gamemode'])->get();
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        $this->checkAdmin();
        $levels = Level::all();
        $gamemodes = Gamemode::all();
        return view('admin.tournaments.create', compact('levels', 'gamemodes'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'gamemode_id' => 'required|exists:gamemodes,id',
            'location' => 'required|string|max:255',
            'quantity_rounds' => 'required|integer|min:1',
            'status' => 'required|in:Запланирован,В процессе,Завершен',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('uploads', 'public');
            $validated['img'] = basename($path);
        }

        $tournament = Tournament::create($validated);

        // Отправляем уведомление о новом турнире
        if ($tournament->status == 'Запланирован') {
            try {
                $vk = new VKService();
                $sent = $vk->notifyNewTournament($tournament->id);
                
                return redirect()->route('admin.tournaments.index')
                    ->with('success', "Турнир успешно создан! Уведомления отправлены {$sent} пользователям.");
            } catch (\Exception $e) {
                \Log::error('Ошибка отправки уведомлений: ' . $e->getMessage());
                return redirect()->route('admin.tournaments.index')
                    ->with('warning', "Турнир создан, но уведомления не отправлены.");
            }
        }

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Турнир успешно создан!');
    }

    public function show(Tournament $tournament)
    {
        $this->checkAdmin();
        $tournament->load(['level', 'gamemode', 'users']);
        return view('admin.tournaments.show', compact('tournament'));
    }

    public function edit(Tournament $tournament)
    {
        $this->checkAdmin();
        $levels = Level::all();
        $gamemodes = Gamemode::all();
        return view('admin.tournaments.edit', compact('tournament', 'levels', 'gamemodes'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'gamemode_id' => 'required|exists:gamemodes,id',
            'location' => 'required|string|max:255',
            'quantity_rounds' => 'required|integer|min:1',
            'status' => 'required|in:Запланирован,В процессе,Завершен',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img')) {
            if ($tournament->img && Storage::disk('public')->exists('uploads/' . $tournament->img)) {
                Storage::disk('public')->delete('uploads/' . $tournament->img);
            }
            
            $path = $request->file('img')->store('uploads', 'public');
            $validated['img'] = basename($path);
        }

        $tournament->update($validated);

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Турнир успешно обновлён!');
    }

    public function destroy(Tournament $tournament)
    {
        $this->checkAdmin();
        
        DB::table('reg_to_tournaments')->where('tournament_id', $tournament->id)->delete();
        DB::table('tournament_matches')->where('tournament_id', $tournament->id)->delete();
        DB::table('tournament_byes')->where('tournament_id', $tournament->id)->delete();
        
        if ($tournament->img && Storage::disk('public')->exists('uploads/' . $tournament->img)) {
            Storage::disk('public')->delete('uploads/' . $tournament->img);
        }

        $tournament->delete();

        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Турнир успешно удалён!');
    }

    public function manage(Tournament $tournament)
    {
        $this->checkAdmin();
        $participants = $tournament->users()->get();
        
        $hasDraw = TournamentMatch::where('tournament_id', $tournament->id)->exists();
        
        $rounds = [];
        $pendingMatches = 0;
        
        if ($hasDraw) {
            $maxRound = TournamentMatch::where('tournament_id', $tournament->id)->max('round') ?? 0;
            
            for ($round = 1; $round <= $maxRound; $round++) {
                $matches = TournamentMatch::where('tournament_id', $tournament->id)
                    ->where('round', $round)
                    ->with(['whitePlayer', 'blackPlayer'])
                    ->get();
                
                $rounds[$round] = $matches;
                
                foreach ($matches as $match) {
                    if ($match->status !== 'played') {
                        $pendingMatches++;
                    }
                }
            }
        }
        
        $points = [];
        foreach ($participants as $p) {
            $userId = $p->id;
            
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
            
            $byePts = TournamentBye::where('tournament_id', $tournament->id)
                ->where('user_id', $userId)
                ->sum('points') ?? 0;
            
            $points[$userId] = $pts + $byePts;
        }
        
        if ($participants->isNotEmpty()) {
            $participants = $participants->sortByDesc(function($user) use ($points) {
                return $points[$user->id] ?? 0;
            })->values();
        }
        
        $currentRound = $hasDraw ? TournamentMatch::where('tournament_id', $tournament->id)->max('round') : 0;
        
        return view('admin.tournaments.manage', compact(
            'tournament',
            'participants',
            'hasDraw',
            'rounds',
            'points',
            'currentRound',
            'pendingMatches'
        ));
    }

    public function draw(Tournament $tournament)
    {
        $this->checkAdmin();
        
        $participants = $tournament->users()->get();
        if ($participants->isEmpty()) {
            return redirect()->route('admin.tournaments.manage', $tournament->id)
                ->with('error', 'Нет участников для жеребьевки!');
        }
        
        $currentRound = TournamentMatch::where('tournament_id', $tournament->id)->max('round') ?? 0;
        $nextRound = $currentRound + 1;
        
        if ($nextRound > $tournament->quantity_rounds) {
            return redirect()->route('admin.tournaments.manage', $tournament->id)
                ->with('error', 'Все туры уже проведены!');
        }
        
        $playerIds = $participants->pluck('id')->toArray();
        shuffle($playerIds);
        
        $pairs = [];
        for ($i = 0; $i < count($playerIds) - 1; $i += 2) {
            $pairs[] = [$playerIds[$i], $playerIds[$i + 1]];
        }
        
        $byePlayer = null;
        if (count($playerIds) % 2 != 0) {
            $byePlayer = end($playerIds);
        }
        
        foreach ($pairs as $pair) {
            TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'round' => $nextRound,
                'white_player_id' => $pair[0],
                'black_player_id' => $pair[1],
                'result' => 'pending',
                'winner_id' => null,
                'status' => 'pending',
                'created_at' => now(),
            ]);
        }
        
        if ($byePlayer) {
            TournamentBye::create([
                'tournament_id' => $tournament->id,
                'user_id' => $byePlayer,
                'round' => $nextRound,
                'points' => 1,
                'created_at' => now(),
            ]);
        }
        
        if ($tournament->status == 'Запланирован') {
            $tournament->update(['status' => 'В процессе']);
        }
        
        return redirect()->route('admin.tournaments.manage', $tournament->id)
            ->with('success', "Жеребьевка {$nextRound}-го тура проведена успешно!");
    }

    public function resetDraw(Tournament $tournament)
    {
        $this->checkAdmin();
        TournamentMatch::where('tournament_id', $tournament->id)->delete();
        TournamentBye::where('tournament_id', $tournament->id)->delete();
        
        if ($tournament->status == 'В процессе') {
            $tournament->update(['status' => 'Запланирован']);
        }
        
        return redirect()->route('admin.tournaments.manage', $tournament->id)
            ->with('success', 'Жеребьевка сброшена!');
    }

    public function updateMatch(Request $request)
    {
        $this->checkAdmin();
        
        $request->validate([
            'match_id' => 'required|exists:tournament_matches,id',
            'result' => 'required|in:white_win,black_win,draw',
        ]);
        
        $match = TournamentMatch::findOrFail($request->match_id);
        
        switch ($request->result) {
            case 'white_win':
                $match->winner_id = $match->white_player_id;
                $match->result = 'white_win';
                break;
            case 'black_win':
                $match->winner_id = $match->black_player_id;
                $match->result = 'black_win';
                break;
            case 'draw':
                $match->winner_id = null;
                $match->result = 'draw';
                break;
        }
        
        $match->status = 'played';
        $match->played_at = now();
        $match->save();
        
        $tournament = $match->tournament;
        $totalMatches = TournamentMatch::where('tournament_id', $tournament->id)->count();
        $playedMatches = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('status', 'played')
            ->count();
        
        if ($totalMatches > 0 && $totalMatches == $playedMatches) {
            $maxRound = TournamentMatch::where('tournament_id', $tournament->id)->max('round') ?? 0;
            if ($maxRound >= $tournament->quantity_rounds) {
                $tournament->update(['status' => 'Завершен']);
            }
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Результат обновлен!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Результат обновлен!');
    }

    public function drawView(Tournament $tournament)
    {
        $this->checkAdmin();
        
        $registrations = $tournament->users()->get();
        $hasDraw = TournamentMatch::where('tournament_id', $tournament->id)->exists();
        
        $rounds = [];
        $points = [];
        
        if ($hasDraw) {
            $maxRound = TournamentMatch::where('tournament_id', $tournament->id)->max('round') ?? 0;
            
            for ($round = 1; $round <= $maxRound; $round++) {
                $matches = TournamentMatch::where('tournament_id', $tournament->id)
                    ->where('round', $round)
                    ->with(['whitePlayer', 'blackPlayer'])
                    ->get();
                
                $rounds[$round] = $matches;
            }
            
            foreach ($registrations as $p) {
                $userId = $p->id;
                
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
                
                $byePts = TournamentBye::where('tournament_id', $tournament->id)
                    ->where('user_id', $userId)
                    ->sum('points') ?? 0;
                
                $points[$userId] = $pts + $byePts;
            }
            
            $registrations = $registrations->sortByDesc(function($user) use ($points) {
                return $points[$user->id] ?? 0;
            })->values();
        }
        
        return view('admin.tournaments.draw', compact(
            'tournament',
            'registrations',
            'hasDraw',
            'rounds',
            'points'
        ));
    }
}