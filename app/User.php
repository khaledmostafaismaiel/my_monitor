<?php

namespace App;

use App\Mail\UserSignedup;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [] ;

    public function family(){
        return $this->belongsTo(Family::class);
    }
}
