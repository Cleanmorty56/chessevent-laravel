<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }
}