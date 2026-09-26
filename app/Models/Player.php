<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    protected $fillable = [
        'player_list_id',
        'name',
        'country'
    ];

    public function countryRecord(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
