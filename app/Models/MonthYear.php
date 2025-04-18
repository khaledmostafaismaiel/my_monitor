<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthYear extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function family(){
        return $this->belongsTo(Family::class);
    }

    public function normalTransactions(){
        return $this->hasMany(NormalTransaction::class);
    }
}
