<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
class ForgotPasswordController extends Controller
{

    use AuthenticatesUsers;

    public function sendForgetpasswordLink(Request $request)
    {
        $resp = $this->validateEmail($request);
        if (!$resp) {
            return response()->json([
                'hasError' => true,
                'message' => 'User not found in our database. Please check your email id',
                'data' => ''
            ],200);
        }

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
        $details = ['email' => $request->email, 'token' => $token, 'link'=>'http://www.awayddings.com/reset-password/'.$token];
        SendEmail::dispatch($details);
        return response()->json([
            'hasError' => false,
            'message' => 'success',
            'data' => "We have e-mailed your password reset link!"
        ],200);
    }


    public function submitResetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);
       

        if ($validator->fails()) {
            return response()->json([
                'hasError' => true,
                'message' => 'Please check all required field',
                'data' => ""
            ],200);
        }       
        $tokenModel = DB::table('password_resets')
                            ->where([
                            'email' => $request->email, 
                            'token' => $request->token
                            ])
                            ->first();

        if(!$tokenModel){
            return response()->json([
                'hasError' => true,
                'message' => 'Token is expired or Invalid',
                'data' => ""
            ],200);
        }

        User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json([
            'hasError' => false,
            'message' => 'success',
            'data' => "successfully password changed"
        ],200);
    }




    protected function validateEmail(Request $request)
    {
        $field = $this->field($request);
        $messages = ["{$this->username()}.exists" => 'The account you are trying to login is not registered or it has been disabled.'];
        $user        = User::where($field, $request->email)->where('status',1)->first();
        if ($user) {
            $currentRole = $user->hasAnyRole(['user']);
            if ($currentRole) {
                $this->validate($request, [
                    $this->username() => "required|exists:users,{$field}"
                ], $messages);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function field(Request $request)
    {
        $email = $this->username();
        return filter_var($request->get($email), FILTER_VALIDATE_EMAIL) ? $email : 'phone';
    }
}
