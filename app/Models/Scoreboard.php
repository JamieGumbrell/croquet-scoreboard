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
        'name1',
        'name2',
        'country1',
        'country2',
        'games1',
        'games2',
        'score1',
        'score2',
        'score3',
        'score4',
        'score5',
        'score6',
        'score7',
        'score8',
        'score9',
        'score10',
    ];

    protected static function booted()
    {
        static::creating(function (Scoreboard $scoreboard) {
            $scoreboard->uid = Str::random(16);
        });
    }

}
