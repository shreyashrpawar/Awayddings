<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\BookingSummary;
use App\Models\User;
use App\Jobs\SendEmailEmiPayments;

class EMIPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EMIPayment:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder for EMI payments before 24hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emis = BookingPaymentDetail::where('status', 1)->where('date', '<=', now()->addDay()) // Retrieve EMIs due within 24 hours
               ->where('email_sent', 0) // Add a flag to avoid sending duplicate emails
               ->orderBy('created_at', 'desc')
               ->get();
            //    dd($emis);
        foreach ($emis as $emi) {
            // dd($emi);
            $booking_payment = BookingPaymentSummary::find($emi->booking_payment_summaries_id);
            $bookings = BookingSummary::find($booking_payment->booking_summaries_id);
            $user = User::find($bookings->user_id);
            $details = ['email' => $user->email,'mailbtnLink' => '', 'mailBtnText' => '',
                    'mailTitle' => 'Reminder For EMI Payments', 'mailSubTitle' => 'Reminder for EMI Payments from Awayddings.', 'mailBody' => 'I hope that you are well. I am contacting you on behalf of Awayddings with regard to the following invoice:'.$emi->amount.'This invoice is due for payment on '.$emi->date.'. Please could you kindly confirm receipt of this invoice and advise as to whether payment has been scheduled.!'];
            SendEmailEmiPayments::dispatch($details);
            
            $emi->email_sent = 1;
            $emi->save(); 
            // \Log::info("Reminder Email sent successfully.");
            exit;
        }
    }
}
