<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventBookingPaymentDetail;
use App\Models\EventBookingPaymentSummary;
use App\Models\EventBookingSummary;
use App\Models\User;
use App\Jobs\SendEmailEmiPayments;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EventEMIPaymentCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventEMIPayment:cron';

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
        $emis = EventBookingPaymentDetail::where('status', '1')
                ->where('date', '<=', now()->addDay()->format('Y-m-d')) // Retrieve EMIs due within 24 hours
                ->where('email_sent', '0') // Add a flag to avoid sending duplicate emails
                ->orderBy('created_at', 'desc')
               ->get();
        if($emis) {
            //    dd($emis);
            foreach ($emis as $emi) {
                // dd($emi);
                $booking_payment = EventBookingPaymentSummary::find($emi->em_booking_payment_summaries_id);
                $bookings = EventBookingSummary::find($booking_payment->em_booking_summaries_id);
                if($bookings->booking_summaries_status != 'rejected' || $bookings->booking_summaries_status != 'cancel') {
                    $user = User::find($bookings->user_id);
                    $details = ['email' => $user->email,'mailbtnLink' => '', 'mailBtnText' => '',
                            'mailTitle' => 'Reminder For EMI Payments', 'mailSubTitle' => 'Reminder for EMI Payments from Awayddings.', 'mailBody' => 'I hope that you are well. I am contacting you on behalf of Awayddings with regard to the following invoice: Rs. '.$emi->amount.' This invoice is due for payment on '.$emi->date.'. Please could you kindly confirm receipt of this invoice and advise as to whether payment has been scheduled.!'];
                    SendEmailEmiPayments::dispatch($details);
                    
                    $emi->email_sent = 1;
                    $emi->save(); 
                    \Log::info("Reminder Email sent successfully.");
                    // exit;
                }
            }
        } else {
            \Log::info("No record found.");
        }
    }
}
