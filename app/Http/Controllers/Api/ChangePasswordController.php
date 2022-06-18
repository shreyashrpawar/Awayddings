<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Rules\MatchOldPasswordRule;
use Hash;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPasswordRule],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return response()->json([
            'hasError' => false,
            'message' => 'success',
            'data' => "Password successfully changed"
        ],200);
    }
}
