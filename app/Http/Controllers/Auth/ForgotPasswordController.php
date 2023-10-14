<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\SendMail;
use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('maintenance_mode');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user) {
            $sent = SendMail::sendPasswordResetLink($request->email, $_SERVER['APP_URL'].'/?token=' . $request->_token . '&mail=' . $request->email . '&res=0');

            if ($sent == null) {
                $result = ['code' => '1'];
            } else {
                $result = ['code' => '0'];
            }
        }else{
            $result = ['email' => 'The user doesnt exists'];
        }

        return response()->json($result);
    }

    public function showLinkRequestForm(){
        if (isModuleActive('Otp') && otp_configuration('otp_on_password_reset')) {
            return view(theme('auth.reset_user_otp'));
        }
        return view(theme('auth.email'));
    }
    
}
