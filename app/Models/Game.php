<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'white_user_id',
        'black_user_id',
        'tournament_id',
        'status',
        'current_fen',
        'last_move_at',
        'started_at',
        'finished_at',
        'winner_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'last_move_at' => 'datetime',
    ];

    public function whiteUser()
    {
        return $this->belongsTo(User::class, 'white_user_id');
    }

    public function blackUser()
    {
        return $this->belongsTo(User::class, 'black_user_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function moves()
    {
        return $this->hasMany(Move::class);
    }

    public function eloHistories()
    {
        return $this->hasMany(EloHistory::class);
    }
}