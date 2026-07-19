<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RegToTournament;
use App\Models\Tournament;
use App\Models\Game;
use App\Models\EloHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Турниры пользователя
        $tournaments = Tournament::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        
        $registrations = RegToTournament::where('user_id', $user->id)
            ->get()
            ->keyBy('tournament_id');
        
        // ===== СТАТИСТИКА ИГР =====
        // Победы
        $wins = Game::where('winner_id', $user->id)
            ->whereIn('status', ['white_win', 'black_win'])
            ->count();
        
        // Поражения
        $losses = Game::where(function($query) use ($user) {
                $query->where('white_user_id', $user->id)
                      ->orWhere('black_user_id', $user->id);
            })
            ->whereIn('status', ['white_win', 'black_win'])
            ->where('winner_id', '!=', $user->id)
            ->where('winner_id', '!=', null)
            ->count();
        
        // Ничьи
        $draws = Game::where(function($query) use ($user) {
                $query->where('white_user_id', $user->id)
                      ->orWhere('black_user_id', $user->id);
            })
            ->where('status', 'draw')
            ->count();
        
        // Всего игр
        $totalGames = Game::where(function($query) use ($user) {
                $query->where('white_user_id', $user->id)
                      ->orWhere('black_user_id', $user->id);
            })
            ->whereIn('status', ['white_win', 'black_win', 'draw'])
            ->count();
        
        $winRate = $totalGames > 0 ? round(($wins / $totalGames) * 100, 1) : 0;
        
        // ===== ИСТОРИЯ ELO =====
        $eloHistory = EloHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // ===== ИСТОРИЯ ИГР =====
        $games = Game::where(function($query) use ($user) {
                $query->where('white_user_id', $user->id)
                      ->orWhere('black_user_id', $user->id);
            })
            ->whereIn('status', ['white_win', 'black_win', 'draw'])
            ->with(['whiteUser', 'blackUser'])
            ->orderBy('finished_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('profile.index', compact(
            'user',
            'tournaments',
            'registrations',
            'wins',
            'losses',
            'draws',
            'totalGames',
            'winRate',
            'eloHistory',
            'games'
        ));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile')
            ->with('success', 'Профиль успешно обновлен!');
    }
    
    public function changePassword()
    {
        return view('profile.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->oldPassword, $user->password)) {
            return back()->withErrors(['oldPassword' => 'Неверный текущий пароль']);
        }
        
        $user->update([
            'password' => Hash::make($request->newPassword)
        ]);
        
        return redirect()->route('profile')
            ->with('success', 'Пароль успешно изменен!');
    }
    
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Удаляем все связи
        \DB::table('reg_to_tournaments')->where('user_id', $user->id)->delete();
        \DB::table('tournament_matches')
            ->where('white_player_id', $user->id)
            ->orWhere('black_player_id', $user->id)
            ->delete();
        \DB::table('tournament_byes')->where('user_id', $user->id)->delete();
        \DB::table('planning')->where('user_id', $user->id)->delete();
        
        Auth::logout();
        $user->delete();
        
        return redirect('/')
            ->with('success', 'Аккаунт успешно удален!');
    }
    
    public function downloadPgn($gameId)
    {
        $user = Auth::user();
        
        $game = Game::where(function($query) use ($user) {
                $query->where('white_user_id', $user->id)
                      ->orWhere('black_user_id', $user->id);
            })
            ->findOrFail($gameId);
        
        $pgn = $this->generatePgn($game);
        
        return response($pgn)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="game_' . $gameId . '.pgn"');
    }
    
    private function generatePgn($game)
    {
        $pgn = "[Event \"Online Chess\"]\n";
        $pgn .= "[Site \"Chess Event\"]\n";
        $pgn .= "[Date \"" . ($game->started_at ? $game->started_at->format('Y.m.d') : date('Y.m.d')) . "\"]\n";
        $pgn .= "[White \"" . ($game->whiteUser->username ?? 'Unknown') . "\"]\n";
        $pgn .= "[Black \"" . ($game->blackUser->username ?? 'Unknown') . "\"]\n";
        $pgn .= "[Result \"" . $this->getPgnResult($game) . "\"]\n";
        $pgn .= "[FEN \"start\"]\n";
        $pgn .= "\n";
        
        $moves = $game->moves()->orderBy('move_number')->get();
        if ($moves->isNotEmpty()) {
            $moveStrings = [];
            $currentNumber = 1;
            $moveIndex = 0;
            
            foreach ($moves as $move) {
                if ($moveIndex % 2 == 0) {
                    $moveStrings[] = $currentNumber . '. ' . $move->move_san;
                } else {
                    $moveStrings[] = $move->move_san;
                    $currentNumber++;
                }
                $moveIndex++;
            }
            
            $pgn .= implode(' ', $moveStrings);
        }
        
        return $pgn;
    }
    
    private function getPgnResult($game)
    {
        switch ($game->status) {
            case 'white_win':
                return '1-0';
            case 'black_win':
                return '0-1';
            case 'draw':
                return '1/2-1/2';
            default:
                return '*';
        }
    }
}