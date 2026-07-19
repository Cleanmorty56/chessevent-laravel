<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegToTournament extends Model
{
    protected $table = 'reg_to_tournaments';

    public $timestamps = false;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'registration_date',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}