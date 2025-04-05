<?php

namespace App;

class NormalTransaction extends Transaction
{
    protected static function booted()
    {
        static::addGlobalScope('normal', function ($query) {
            $query->where('is_blue_print', false);
        });
    }
}
