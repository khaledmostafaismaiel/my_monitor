<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otps';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
