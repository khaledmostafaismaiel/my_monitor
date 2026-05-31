<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

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
        return $this->hasMany(OTP::class);
    }
}
