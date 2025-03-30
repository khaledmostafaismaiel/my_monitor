<?php

namespace App\Http\Controllers;

use App\Mail\UserSignedup;
use Illuminate\Http\Request;
use App\User ;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public function sign_in()
    {
        if(\Auth::attempt(['email' => \request('user_name'), 'password' => \request('password')])){
            return redirect('/');
        }else{
            return redirect('/');
        }
    }

    public function sign_out()
    {
        auth()->logout();

        return redirect('/');
    }
}

