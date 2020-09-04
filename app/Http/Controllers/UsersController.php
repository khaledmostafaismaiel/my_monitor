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
                'first_name'=> 'required|min:2',
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
//                'user_id'=> ,
                'first_name'=> sql_sanitize(strtolower(trim(request('first_name')))) ,
                'second_name'=> sql_sanitize(strtolower(trim(request('second_name')))),
                'user_name'=> sql_sanitize(strtolower(trim(request('user_name')))) ,
                'hashed_password'=> sql_sanitize(bcrypt(request('password'))),
                'created_at'=> date("Y-m-d H:m:s")

        ]);

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
        auth()->logout();
        dump("you came from users/show not from users/process_sign_out");
        return view('auth/login');

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
        if(\Auth::attempt(['user_name' => \request('user_name'), 'password' => \request('password')])){
            session()->flash('message','Welcome');
            return redirect('/');
        }else{
            session()->flash('message','Sorry Try Again');
            return redirect('/users/create');
        }
    }
    public function process_sign_out()
    {

        auth()->logout();
        dd("sdao");

        return redirect('/sign_in');
    }
}

