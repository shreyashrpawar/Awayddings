<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $mailbtnLink;
    protected $mailBtnText;
    protected $mailTitle;
    protected $mailSubTitle;
    protected $mailBody;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$mailSubTitle, $mailbtnLink,$mailBtnText,$mailTitle,$mailBody)
    {
        //
        $this->email = $email;
        $this->mailbtnLink = $mailbtnLink;
        $this->mailBtnText = $mailBtnText;
        $this->mailTitle = $mailTitle;
        $this->mailSubTitle = $mailSubTitle;
        $this->mailBody = $mailBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['mailTitle'] = $this->mailTitle;
        $data['mailSubTitle'] =  $this->mailSubTitle;
        $data['mailBody'] = $this->mailBody;
        $data['mailBtnText'] =  $this->mailBtnText;
        $data['mailBtnUrl'] = $this->mailbtnLink;

        // $verificationUrl = route('email.verify', encrypt($this->mailbtnLink));

        // return $this->markdown('emails.generic')
        //     ->with($data);

        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject($this->mailTitle)
            ->view('emails.generic',$data);
            // return $this->markdown('emails.email-verification')
            // ->subject('Email Verification');
    }
}
