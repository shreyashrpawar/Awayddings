<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\EventBookingSummary;
use App\Models\EventCustomerBookingInvoice;
use App\Mail\installmentEmail;
use Mail;
use PDF;
use Storage;

class EventGeneratePDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bookings = $this->details['data'];
        //dd($bookings);
        $pdf = PDF::loadView('pdf.event-customer-invoice', compact('bookings'));
        //Storage::put('public/pdf/customer_invoice_' . $bookings->id . '.pdf', $pdf->output());
         $filePath = 'invoice/customer_invoice_' .  $bookings->id . '.pdf';
         Storage::disk('s3')->put($filePath, $pdf->output(), 'public');
         $pdfLink=Storage::disk('s3')->url($filePath);

        //send mail after PDF genarate
        $email = new installmentEmail($this->details['mailData']['installment_no'],
        $this->details['mailData']['total_paid'],$this->details['mailData']['total_due'],
        $this->details['mailData']['installment_amount'],$this->details['mailData']['next_installment_date'],
        $this->details['mailData']['remarks'],$pdfLink,"Download Invoice");
       
        Mail::to($this->details['mailData']['email'])->send($email);

        $invoice = EventCustomerBookingInvoice::updateOrCreate([
            'em_booking_summary_id'   => $bookings->id
        ],[
            'invoice_url' => $pdfLink            
        ]);
    }
}
