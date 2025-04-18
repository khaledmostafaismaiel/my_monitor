<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use App\Models\OTP;
use Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function register()
    {
        return view('auth.login');
    }

    public function sign_in()
    {
        if(\Auth::attempt(['email' => \request('user_name'), 'password' => \request('password')])){
            return redirect('/');
        }else{
            return redirect()->back()->withErrors(['email' => 'Email or Password is not correct.']);

            return redirect('/login');
        }
    }

    public function sign_out()
    {
        auth()->logout();

        return redirect('/');
    }

    public function sign_up(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
            'family_option' => 'required|string|in:create,join',
            'family_id' => 'required_if:family_option,join|nullable|string|max:255',
            'family_name' => 'required_if:family_option,create|nullable|string|max:255',
        ]);

        if($request->family_option == "join"){
            $family = Family::find($request->family_id);
            if($family){
                $user = User::create(
                    array_merge(
                        $request->toArray(),
                        [
                            'family_id'=> $family->id,
                        ]
                    )
                );

                $user->otps()->create(
                    [
                        "body"=> (string)rand(000000, 999999),
                        "expire_at"=> now()->addMinutes(10),
                    ]
                );
            }else{

            }

            return view('auth.verify');
        }else{
            $family = Family::create(
                [
                    "name"=>$request->family_name,
                ]
            );

            $user = User::create(
                array_merge(
                    $request->toArray(),
                    [
                        'family_id'=> $family->id,
                        'email_verified_at'=> now(),
                    ]
                )
            );

            Auth::login($user);

            return redirect('/');

        }
    }

    public function verify_otp(Request $request)
    {
        $otp = OTP::where("body", $request->otp1.$request->otp2.$request->otp3.$request->otp4.$request->otp5.$request->otp6)->first();

        if($otp){
            $user = $otp->user;

            Auth::login($user);

            $otp->delete();

            return redirect('/');
        }else{
            return redirect('/');

            return redirect()->back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    }
}

