<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingSummary;

class ApprovalEmail extends Mailable
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
        $data['mailTitle'] = 'Congradulations';
        $data['mailSubTitle'] =  'Booking Approval mail';
        $data['mailBody'] = 'Congradulations! Your booking has been approved.';
        $data['mailBtnText'] =  '';
        $data['mailBtnUrl'] = '';
        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject($data['mailSubTitle'])
            ->view('emails.approval')->with([
                'bookings' => $this->bookings,
                'data' => $data,
              ]);

        // $data['mailTitle'] = 'Thank you';
        // $data['mailSubTitle'] =  'Booking Approval mail';
        // // $data['installment_no'] = $this->installment_no;
        
        // $data['mailBtnText'] =  'test';
        // $data['mailBtnUrl'] = 'test';
        // $bookings = $this->bookings;
        // // dd($this->bookings);
        // // return $this->view('view.name');
        // return $this->view('emails.approval')->with([
        //     'bookings' => $bookings,
        //     'data' => $data,
        //   ])
        //     ->subject('Welcome to My Website');
    }
}
