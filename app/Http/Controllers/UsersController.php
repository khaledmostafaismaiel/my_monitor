<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use App\Models\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function register()
    {
        return Inertia::render('Auth/Login');
    }

    public function sign_in(Request $request)
    {
        $credentials = $request->validate([
            'user_name' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $credentials['user_name'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['user_name' => 'Email or password is incorrect.']);
    }

    public function sign_out(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/users/register');
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

        if ($request->family_option === 'join') {
            $family = Family::find($request->family_id);

            if (!$family) {
                return back()->withErrors(['family_id' => 'No family found with that ID.']);
            }

            $user = User::create(array_merge(
                Arr::except($request->toArray(), ['password', 'password_confirmation', 'terms']),
                [
                    'family_id' => $family->id,
                    'password' => bcrypt($request->password),
                ],
            ));

            $user->otps()->create([
                'body' => (string) random_int(100000, 999999),
                'expire_at' => now()->addMinutes(10),
            ]);

            return redirect()->route('verification.notice')
                ->with('pending_user_email', $user->email);
        }

        $family = Family::create(['name' => $request->family_name]);

        $user = User::create(array_merge(
            Arr::except($request->toArray(), ['password', 'password_confirmation', 'terms']),
            [
                'family_id' => $family->id,
                'email_verified_at' => now(),
                'password' => bcrypt($request->password),
            ],
        ));

        Auth::login($user);

        return redirect('/');
    }

    public function show_verify_otp(Request $request)
    {
        return Inertia::render('Auth/VerifyOtp', [
            'email' => $request->session()->get('pending_user_email'),
        ]);
    }

    public function verify_otp(Request $request)
    {
        $code = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;

        $otp = OTP::where('body', $code)->first();

        if (!$otp) {
            return back()->withErrors(['otp1' => 'Invalid OTP. Please try again.']);
        }

        $user = $otp->user;
        $user->update(['email_verified_at' => now()]);

        Auth::login($user);
        $otp->delete();

        return redirect('/');
    }
}
