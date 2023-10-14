<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function reset(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        $user = User::where('email', $request->email)->first();

        if(!isset(auth()->user()->id)) {
            $this->guard()->login($user);
            Toastr::success(__('auth.logged_in_successfully'), 'Login');
        }else{
            Toastr::success(__('auth.pass_changed_successfully'), 'Change password');
        }

        return redirect()->to('/profile');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');
        
        return view(theme('auth.reset'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
