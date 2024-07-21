<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, User $user)
    {
        $expire_time_db = $user->email_verification_token_expires_at;

        $expire_at = Carbon::parse($user->email_verification_token_expires_at)->format('Y-m-d');
        $frontend_url = Config::get('app.frontend_url');
        if (!$expire_at || Carbon::now() >= $expire_at) {
            return redirect()->route('redirect-to-frontend')->with('error', 'Email verification link has expired.');
        }

        if ($user->is_verified) {
            return redirect()->route('redirect-to-frontend')->with('info', 'Your email is already verified.');
        }

        $user->markEmailAsVerified();
            return redirect()->route('redirect-to-frontend')->with('success', 'Email verified successfully!');
    }

    public function redirectToFrontend()
    {
        $frontendUrl = Config::get('app.frontend_url'); // Replace with your actual frontend URL
        
        return redirect()->away($frontendUrl);
    }
}