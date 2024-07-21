<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPasswordRule;
use Illuminate\Http\Request;
use Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPasswordRule],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return back()->with('message', 'Password change successfully.');
    }
}
