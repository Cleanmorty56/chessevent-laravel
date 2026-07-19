<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EloHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'game_id',
        'elo_before',
        'elo_after',
        'change',
        'reason',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}