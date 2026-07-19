<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'img',
        'name',
        'description',
        'gamemode_id',
        'location',
        'quantity_rounds',
        'status',
        'level_id',
    ];

    public function gamemode()
    {
        return $this->belongsTo(Gamemode::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function registrations()
    {
        return $this->hasMany(RegToTournament::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reg_to_tournaments', 'tournament_id', 'user_id')
                    ->withPivot('registration_date');
        // Убрали ->withTimestamps()
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function matches()
    {
        return $this->hasMany(TournamentMatch::class);
    }

    public function byes()
    {
        return $this->hasMany(TournamentBye::class);
    }

    public function isAvailableForRegistration()
    {
        return $this->status !== 'Завершен' && $this->status !== 'В процессе';
    }
}