<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planning extends Model
{
    protected $fillable = [
        'content',
        'organizer',
        'user_id',
        'gamemode_id',
        'imageFile',
        'quantity_rounds',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gamemode()
    {
        return $this->belongsTo(Gamemode::class);
    }
}