<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name' ,
        'second_name' ,
        'user_name' ,
        'hashed_password' ,
        'background_image' ,

    ] ;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function Expenses(){//return expenses which belongs to that user
        return $this->hasMany(Expense::class);
    }
    public function Backgrounds(){//return backgrounds which belongs to that user
        return $this->hasMany(Background::class);
    }
}
