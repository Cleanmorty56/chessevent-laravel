<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentBye extends Model
{
    protected $table = 'tournament_byes';

    public $timestamps = false;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'round',
        'points',
        'created_at',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Добавьте этот метод для удобства
    public static function getPointsForUser($tournamentId, $userId)
    {
        return self::where('tournament_id', $tournamentId)
            ->where('user_id', $userId)
            ->sum('points') ?? 0;
    }
}