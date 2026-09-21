<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Country extends Model
{
    protected $fillable = [
        'name',
        'link',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->link) {
            return null;
        }

        return str_starts_with($this->link, 'countries/')
            ? Storage::disk('public')->url($this->link)
            : $this->link;
    }
}
