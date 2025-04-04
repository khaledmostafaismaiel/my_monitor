<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthYear extends Model
{
    protected $guarded = [];


    public function family(){
        return $this->belongsTo(Family::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
