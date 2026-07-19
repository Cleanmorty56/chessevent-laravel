<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentMatch extends Model
{
    protected $table = 'tournament_matches';
    public $timestamps = false;

    protected $fillable = [
        'tournament_id',
        'round',
        'white_player_id',
        'black_player_id',
        'result',
        'winner_id',
        'status',
        'played_at',
        'created_at',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function whitePlayer()
    {
        return $this->belongsTo(User::class, 'white_player_id');
    }

    public function blackPlayer()
    {
        return $this->belongsTo(User::class, 'black_player_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function isPlayed()
    {
        return $this->status === 'played';
    }

    public function getResultLabel()
    {
        if ($this->result == 'white_win') return 'Победа белых';
        if ($this->result == 'black_win') return 'Победа черных';
        if ($this->result == 'draw') return 'Ничья';
        return '—';
    }

    // Вспомогательный метод для проверки завершена ли партия
    public function isFinished()
    {
        return $this->status === 'played';
    }
}