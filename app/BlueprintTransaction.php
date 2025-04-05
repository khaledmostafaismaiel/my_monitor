<?php

namespace App;

class BlueprintTransaction extends Transaction
{
    protected static function booted()
    {
        static::addGlobalScope('blueprints', function ($query) {
            $query->where('is_blue_print', true);
        });
    }
}
