<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamemode extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'control_time'];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }
}