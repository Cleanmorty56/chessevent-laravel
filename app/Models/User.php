<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'elo',
        'region_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Связи
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function gamesAsWhite()
    {
        return $this->hasMany(Game::class, 'white_user_id');
    }

    public function gamesAsBlack()
    {
        return $this->hasMany(Game::class, 'black_user_id');
    }

    public function gamesWon()
    {
        return $this->hasMany(Game::class, 'winner_id');
    }

    public function moves()
    {
        return $this->hasMany(Move::class);
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'reg_to_tournaments', 'user_id', 'tournament_id')
                    ->withPivot('registration_date')
                    ->withTimestamps();
    }

    public function eloHistory()
    {
        return $this->hasMany(EloHistory::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }

    public function tournamentMatchesAsWhite()
    {
        return $this->hasMany(TournamentMatch::class, 'white_player_id');
    }

    public function tournamentMatchesAsBlack()
    {
        return $this->hasMany(TournamentMatch::class, 'black_player_id');
    }

    public function tournamentMatchesWon()
    {
        return $this->hasMany(TournamentMatch::class, 'winner_id');
    }

    public function tournamentByes()
    {
        return $this->hasMany(TournamentBye::class);
    }

    public function tournamentSubscriptions()
    {
    return $this->hasMany(TournamentSubscription::class);
    }

    public function subscribedTournaments()
    {
    return $this->belongsToMany(Tournament::class, 'tournament_subscriptions')
        ->withPivot('notify_draw', 'notify_start', 'notify_result')
        ->withTimestamps();
    }
}