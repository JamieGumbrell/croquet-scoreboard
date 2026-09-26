<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerList extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'enabled'
    ];
}
