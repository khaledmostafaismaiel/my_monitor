<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "password",
        "email_verified_at",
        "family_id",
        
    ] ;

    public function family(){
        return $this->belongsTo(Family::class);
    }

    public function otps()
    {
        return $this->hasMany(OTPS::class);
    }
}
