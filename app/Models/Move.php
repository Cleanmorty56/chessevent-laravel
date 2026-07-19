<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'game_id',
        'user_id',
        'move_number',
        'move_san',
        'move_fen',
        'created_at',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}