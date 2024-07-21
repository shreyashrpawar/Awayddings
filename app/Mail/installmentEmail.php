<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class installmentEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $installment_no;
    protected $total_paid;
    protected $total_due;
    protected $installment_amount;
    protected $next_installment_date;
    protected $remarks;
    protected $mailbtnLink;
    protected $mailBtnText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($installment_no,$total_paid,$total_due,$installment_amount,
    $next_installment_date,$remarks,$mailbtnLink,$mailBtnText)
    {
        //
        $this->installment_no = $installment_no;
        $this->total_paid = $total_paid;
        $this->total_due = $total_due;
        $this->installment_amount = $installment_amount;
        $this->next_installment_date = $next_installment_date;
        $this->remarks = $remarks;
        $this->mailbtnLink = $mailbtnLink??"";
        $this->mailBtnText = $mailBtnText??"";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['mailTitle'] = 'Thank you';
        $data['mailSubTitle'] =  'Installment Payment';
        $data['installment_no'] = $this->installment_no;
        $data['total_paid'] =  $this->total_paid;
        $data['total_due'] = $this->total_due;
        $data['installment_amount'] = $this->installment_amount;
        $data['next_installment_date'] = $this->next_installment_date;
        $data['remarks'] = $this->remarks;
        $data['mailBtnText'] =  $this->mailBtnText;
        $data['mailBtnUrl'] = $this->mailbtnLink;

        return $this->from(env('MAIL_FROM_ADDRESS'), 'Awayddings')
            ->subject('Installment Payment')
            ->view('emails.installment-payment',$data);
    }
}
