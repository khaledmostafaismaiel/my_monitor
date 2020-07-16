<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    public function user(){//return Backgrounds which belongs to that user
        return $this->belongsTo(User::class);
    }
}
