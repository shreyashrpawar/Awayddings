<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectionMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        $data['mailTitle'] = 'Rejected Bookings';
        $data['mailSubTitle'] = 'You booking has been rejected';
        $data['mailBody'] = 'Your booking has been rejected. Please contact with Administration for further query.';
        $data['mailBtnText'] = '';
        $data['mailBtnUrl'] = '';
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject('Rejected Bookings')
            ->view('emails.rejection',$data);
    }
}
