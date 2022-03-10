<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

   public function register(Request  $request){

       $data = [
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password)
       ];

       $userResp = User::create($data);
       $userResp->assignRole('user');

       return $request->response()->json([
           'success' => true,
           'message' => 'Successfully Saved',
       ]);
   }

   public function login(Request  $request){
       return $request->all();
   }
}
