<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Family extends Model
{
    protected $guarded = [] ;

    protected $table = 'families';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($family) {
            if (!$family->id) {
                $family->id = (string) Str::uuid();
            }
        });
    }

    public function monthYears()
    {
        return $this->hasMany(MonthYear::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
