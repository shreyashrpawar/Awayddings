<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class EventApprovalEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $bookings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['mailTitle'] = 'Congratulations';
        $data['mailSubTitle'] =  'Congratulations! Your booking has been approved.';
        $data['mailBody'] = 'Congratulations! Your booking has been approved.';
        $data['mailBtnText'] = 'Check Details';
        $data['mailBtnUrl'] = Config::get('app.frontend_url');
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject($data['mailSubTitle'])
            ->view('emails.event_approvals')->with([
                'bookings' => $this->bookings,
                'data' => $data,
              ]);
    }
}
