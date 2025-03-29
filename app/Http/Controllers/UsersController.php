<?php

namespace App\Http\Controllers;

use App\Mail\UserSignedup;
use Illuminate\Http\Request;
use App\User ;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    public function store(Request $request)
    {
        $valid = request()->validate(
            [
                'first_name'=> 'required|min:2',
                'second_name'=> ['required' , 'min:2' ] ,
                'user_name'=> ['required' , 'min:3', 'email' ] ,
                'password'=> ['required' , 'confirmed' , 'min:8'] ,
                'not_robot'=> ['required' ] ,
                'terms_of_conditions'=> ['required' ] ,

            ]
        );

        User::create([
                'first_name'=> request('first_name') ,
                'last_name'=> request('second_name'),
                'email'=> request('user_name') ,
                'password'=> bcrypt(request('password')),
        ]);

        return redirect('login');
    }

    public function sign_in()
    {
        if(\Auth::attempt(['email' => \request('user_name'), 'password' => \request('password')])){
            return redirect('/');
        }else{
            return redirect('/');
        }
    }

    public function process_sign_out()
    {
        auth()->logout();

        return redirect('/');
    }
}

