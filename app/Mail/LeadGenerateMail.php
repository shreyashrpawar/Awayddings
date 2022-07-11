<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadGenerateMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
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
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject("Enquiry from Landing page")
            ->view('emails.leads',$this->data);
    }
}
