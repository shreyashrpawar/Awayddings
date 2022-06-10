<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendGenricEmail;
use App\Models\User;
use Illuminate\Http\Request;

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
           'password' => bcrypt($request->password)
       ];
       $userResp = User::create($data);
       $userResp->assignRole('user');

       //welcome mail trigger
       $details = ['email' => $request->email,'mailbtnLink' => '', 'mailBtnText' => '',
        'mailTitle' => 'Thank you for registration','mailBody' => 'We are So happy you are here. Start a journey with us'];
       SendGenricEmail::dispatch($details);

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
}
