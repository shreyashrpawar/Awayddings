<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Http\Request;
use DB; 
use Carbon\Carbon; 
use Illuminate\Support\Str;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ForgotPasswordController extends Controller
{

    use AuthenticatesUsers;


    public function index(){
        return view('auth.forgot-password');
    }

    public function field(Request $request)
    {
        $email = $this->username();
        return filter_var($request->get($email), FILTER_VALIDATE_EMAIL) ? $email : 'phone';
    }


    protected function validateLogin(Request $request)
    {
        $field       = $this->field($request);
        $messages    = ["{$this->username()}.exists" => 'The account you are trying to login is not registered or it has been disabled.'];
        $user        = User::where($field, $request->email)->where('status',1)->first();
        if($user){
            $currentRole =  $user->hasAnyRole(['vendor','admin']);
            if ($currentRole)
            {
                $this->validate($request,[
                    $this->username() => "required|exists:users,{$field}"
                ], $messages);
                return true;
            } else {
                return false;
            }
        }
        else{
            return false;
        }

    }


    public function forgotPasswordSubmit(Request $request){
        $resp = $this->validateLogin($request);

        if (!$resp) { $this->sendFailedLoginResponse($request); }

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);

        $details = ['email' => $request->email,'token' => $token, 'link' => url('reset-password/'.$token)];
        SendEmail::dispatch($details);
        return back()->with('message', 'We have e-mailed your password reset link!');        
        
    }


    public function showResetPasswordForm($token) { 
        $tokenModel = DB::table('password_resets')
                            ->where(['token' => $token])
                            ->first();

        if(!$tokenModel){
            return redirect('/login')->with('error', 'Link has been expired');
        }
        $mailCreateDateTime = Carbon::parse($tokenModel->created_at);
        if($mailCreateDateTime->diffInDays(Carbon::now())>0){
            DB::table('password_resets')->where(['token'=> $token])->delete();
            return redirect('/login')->with('error', 'Link has been expired');
        }
        return view('auth.forget-password-link', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $tokenModel = DB::table('password_resets')
                            ->where([
                            'email' => $request->email, 
                            'token' => $request->token
                            ])
                            ->first();

        if(!$tokenModel){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}
