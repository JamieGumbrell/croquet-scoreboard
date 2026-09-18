<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Scoreboard extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'color',
        'ball_color',
        'design',
        'width',
        'scoretype',
        'gametype',
    ];

    protected static function booted()
    {
        static::creating(function (Scoreboard $scoreboard) {
            $scoreboard->uid = Str::random(16);
        });
    }

}
