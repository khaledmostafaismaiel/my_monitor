<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlueprintTransaction extends Transaction
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope('blueprints', function ($query) {
            $query->where('is_blue_print', true);
        });
    }
}
