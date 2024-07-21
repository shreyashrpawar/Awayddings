<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendGenericEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Mail;
use App\Mail\EmailVerification;

use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{

   public function register(Request  $request){
       $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|',
            'password' => 'required|',
       ]);

       $data = [
           'name' => $request->name,
           'email' => $request->email,
           'phone' => $request->phone,
           'password' => bcrypt($request->password),
        //    'email_verification_token' => Str::random(60),
        //    'email_verification_token_expires_at' => Carbon::now()->addDay()
       ];
       $userResp = User::create($data);
       $userResp->assignRole('user');
       $userResp->generateEmailVerificationToken();

    // Send the verification email
    // Mail::to($userResp->email)->send(new EmailVerification($userResp));

       //welcome mail trigger
       $details = ['email' => $request->email,'mailbtnLink' => $userResp->getEmailVerificationUrl(), 'mailBtnText' => 'Click here to verify.',
        'mailTitle' => 'Welcome!', 'mailSubTitle' => 'We are excited to have you get started.', 'mailBody' => 'We are so happy you are here. Start a journey with us'];
       SendGenericEmail::dispatch($details);

       return response()->json([
           'success' => true,
           'message' => 'Successfully Saved',
       ]);
   }
   public function login(Request  $request){
       $username = $request->username;
       $password = $request->password;
      $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email': 'phone';
      $resp = User::role('user')->select('id','name','email','phone')->where($field,$username)->first();
      if($token = JWTAuth::attempt([$field =>  $username, 'password' => $password]))
      {
          return response()->json([
              'success' => true,
              'message' => 'Successfully Logged in',
              'data' => $resp,
              'token' => $token
          ],200);
      }else{
          return response()->json([
              'success' => false,
              'message' => 'Invalid username and password combination'
          ],401);
      }
   }

   public function userInformation(Request  $request){

       $user = auth()->user();
       return response()->json([
           'hasError' => false,
           'message' => 'success',
           'data' => $user
       ],200);
   }
}
