<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    public function transactions()
    {
        return $this->hasMAny(Transaction::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
