<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCancelEmail extends Mailable
{
    use Queueable, SerializesModels;
    // protected $email;
    // protected $mailbtnLink;
    // protected $mailBtnText;
    // protected $mailTitle;
    // protected $mailSubTitle;
    // protected $mailBody;

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
        $data['mailTitle'] = 'Canceled Bookings';
        $data['mailSubTitle'] = 'You booking has been Canceled';
        $data['mailBody'] = 'Your booking has been Canceled. Please contact with Administration for further query.';
        $data['mailBtnText'] = '';
        $data['mailBtnUrl'] = '';
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject('Canceled Bookings')
            ->view('emails.cancel',$data);
    }
}
