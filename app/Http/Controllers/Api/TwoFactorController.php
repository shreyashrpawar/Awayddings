<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }
    public function sendOTP(Request $request)
    {
        // Validate request data
        $request->validate([
            'mobileNumber' => 'required|string',
        ]);

        // Extract phone number from request
        $phoneNumber = $request->input('mobileNumber');
        $templateName = 'Kesari Awayddings';
        // Call service method to send OTP
        $response = $this->twoFactorService->sendOTP($phoneNumber,$templateName);

        // Return response from service to client
        return response()->json($response);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'sessionId' => 'required|string',
            'otp' => 'required|string',
        ]);

        // Extract data from request
        $sessionId = $request->input('sessionId');
        $otp = $request->input('otp');

        // Call service method to verify OTP
        $response = $this->twoFactorService->verifyOTP($sessionId, $otp);

        // Return response from service to client
        return response()->json($response);
    }
}
