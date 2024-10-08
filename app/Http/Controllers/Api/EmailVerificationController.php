<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, User $user)
    {
        $expire_time_db = $user->email_verification_token_expires_at;
        $expire_at = Carbon::parse($user->email_verification_token_expires_at)->format('Y-m-d');
        
        if (!$expire_at || Carbon::now() >= $expire_at) {
            // Token has expired
            return redirect()->route('home')->with('error', 'Email verification link has expired.');
        }

        if ($user->is_verified) {
            // User is already verified
            return redirect()->route('home')->with('info', 'Your email is already verified.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('home')->with('success', 'Email verified successfully!');
    }
}
