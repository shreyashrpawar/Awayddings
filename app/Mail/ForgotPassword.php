<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $token;
    protected $email;
    protected $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$email,$link)
    {
        $this->token = $token;
        $this->email = $email;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['mailTitle'] = 'Thank you';
        $data['mailSubTitle'] = 'FOR PASSWORD RESET REQUEST';
        $data['mailBody'] = 'You are receiving this email because we received a password reset request for your account.';
        $data['mailBtnText'] = 'Reset Password';
        $data['mailBtnUrl'] = $this->link.'?email='.$this->email;
        return $this->from('info@awayddings.com', 'Awayddings')
            ->subject('Reset Password')
            ->view('emails.reset-password',$data);
    }
}
