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
        $data['mailTitle'] = 'RESET YOUR PASSWORD';
        $data['mailSubTitle'] = 'You have requested to reset your password';
        $data['mailBody'] = 'We cannot simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions.';
        $data['mailBtnText'] = 'Reset Password';
        $data['mailBtnUrl'] = $this->link.'?email='.$this->email;
        return $this->from('support@dosetap.com', 'Awayddings')
            ->subject('Reset Password')
            ->view('emails.generic-template',$data);
    }
}
