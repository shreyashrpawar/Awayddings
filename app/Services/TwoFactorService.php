<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TwoFactorService
{
    protected $baseUrl = 'https://2factor.in/API/V1';

    public function sendOTP($phoneNumber,$templateName)
    {
        $response = Http::post("{$this->baseUrl}/{$this->getApiKey()}/SMS/{$phoneNumber}/AUTOGEN/{$templateName}");

        return $response->json();
    }

    public function verifyOTP($sessionID, $otp)
    {
        $response = Http::get("{$this->baseUrl}/{$this->getApiKey()}/SMS/VERIFY/{$sessionID}/{$otp}");

        return $response->json();
    }

    private function getApiKey()
    {
        return config('services.2factor.api_key');
    }
}
