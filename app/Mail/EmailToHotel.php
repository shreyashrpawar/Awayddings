<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mail;

class EmailToHotel extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $name;
    protected $phone;
    protected $check_in;
    protected $check_out;
    protected $adult;
    protected $mailbtnLink;
    protected $mailBtnText;
    protected $booking_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$name,$phone,$check_in,$check_out,$adult,$mailbtnLink,$mailBtnText,$booking_id)
    {
        $this->email = $email;
        $this->name = $name;
        $this->phone = $phone;
        $this->check_in = $check_in;
        $this->check_out = $check_out;
        $this->adult = $adult;
        $this->mailbtnLink = $mailbtnLink??"";
        $this->mailBtnText = $mailBtnText??"";
        $this->booking_id= $booking_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        $data['mailTitle'] = 'New Booking Confirmation';
        $data['mailSubTitle'] =  'New booking from Awayddings';
        $data['email'] = $this->email;
        $data['name'] =  $this->name;
        $data['phone'] =  $this->phone;
        $data['check_in'] = $this->check_in;
        $data['check_out'] = $this->check_out;
        $data['adult'] = $this->adult;
        $data['mailBtnText'] =  $this->mailBtnText;
        $data['mailBtnUrl'] = $this->mailbtnLink;

        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject($data['mailTitle'])
            ->view('emails.booking_data_to_hotel',$data);
    }
}
