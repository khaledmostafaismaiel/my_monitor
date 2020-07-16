<?php

namespace App\Http\Controllers;

use App\Mail\UserSignedup;
use Illuminate\Http\Request;
use App\User ;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sign_up');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = request()->validate(
            [
                'first_name'=> ['required' , 'min:2'] ,
                'second_name'=> ['required' , 'min:2' ] ,
                'user_name'=> ['required' , 'min:3' ] ,
                'password'=> ['required' , 'confirmed' , 'min:8'] ,
                'not_robot'=> ['required' ] ,
                'terms_of_conditions'=> ['required' ] ,

            ]
        );
//        return request()->all();
        $user = new User() ;
        $user->create([
                'first_name'=> request('first_name') ,
                'second_name'=> request('second_name'),
                'user_name'=> request('user_name') ,
                'hashed_password'=> request('password'),
                'created_at'=> date("Y-m-d H:m:s")

        ]);

        $user->first_name = request('first_name') ;
        $user->second_name = request('second_name') ;
        $user->user_name = request('user_name') ;
        $user->hashed_password = request('password') ;

        Mail::to('khaledmostafa297@gmail.com')->send(

            new UserSignedup($user)
        );
        return redirect('sign_in');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function process_sign_in()
    {
        var_dump(request()->all());
    }
}

