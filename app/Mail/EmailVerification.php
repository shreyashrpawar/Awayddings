<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    public $userResp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userResp)
    {
        $this->userResp = $userResp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.email-verification')->subject('Email Verification');
        // $verificationUrl = route('email.verify', encrypt($this->user->verification_token));

        return $this->markdown('emails.email-verification')
            ->with([
                'verificationUrl' => $this->userResp->getEmailVerificationUrl(),
            ]);
    }
}
