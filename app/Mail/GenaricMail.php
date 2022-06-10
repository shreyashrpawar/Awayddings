<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenaricMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $mailbtnLink;
    protected $mailBtnText;
    protected $mailTitle;
    protected $mailBody;

    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $mailbtnLink,$mailBtnText,$mailTitle,$mailBody)
    {
        //
        $this->email = $email;
        $this->mailbtnLink = $mailbtnLink;
        $this->mailBtnText = $mailBtnText;
        $this->mailTitle = $mailTitle;
        $this->mailBody = $mailBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['mailTitle'] = 'Thank you';
        $data['mailSubTitle'] =  $this->mailTitle;
        $data['mailBody'] = $this->mailBody;
        $data['mailBtnText'] =  $this->mailBtnText;
        $data['mailBtnUrl'] = $this->mailbtnLink;
        return $this->from('info@awayddings.com', 'Awayddings')
            ->subject($this->mailTitle)
            ->view('emails.reset-password',$data);
    }
}
