<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingSummary;
use App\Models\CustomerBookingInvoice;
use App\Mail\installmentEmail;
use Mail;

use PDF;
use Storage;
class GeneratePDF implements ShouldQueue
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
        $pdf = PDF::loadView('pdf.customer-invoice', compact('bookings'));
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

        $invoice = CustomerBookingInvoice::updateOrCreate([
            'booking_summary_id'   => $bookings->id
        ],[
            'invoice_url' => $pdfLink            
        ]);
    }
}
