<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NormalTransaction extends Transaction
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope('normal', function ($query) {
            $query->where('is_blue_print', false);
        });
    }
}
