<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\EventBookingPaymentDetail;
use App\Models\EventPreBookingSummary;
use App\Models\EventBookingSummary;

use Carbon\Carbon;

class evetCancelBookingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventCancelBooking:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel expired bookings that have not been paid within 48 hours';

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
        $expiredBookings = DB::table('em_prebooking_summaries')
        ->join('em_booking_summaries', 'em_prebooking_summaries.id', '=', 'em_booking_summaries.em_prebooking_summaries_id')
        ->join('em_booking_payment_summaries', 'em_booking_summaries.id', '=', 'em_booking_payment_summaries.em_booking_summaries_id')
        ->join('em_booking_payment_details', 'em_booking_payment_details.em_booking_payment_summaries_id', '=', 'em_booking_payment_summaries.id')
        // ->select('em_booking_payment_details.*','em_prebooking_summaries.id as pre_booking_id')
        ->select('em_booking_payment_details.*','em_prebooking_summaries.id as pre_booking_id','em_booking_summaries.id as booking_summary_id')
        ->where('em_prebooking_summaries.pre_booking_summary_status_id', 2)
        ->where('em_booking_payment_details.created_at', '<=', Carbon::now()->subHours(48))
        // ->where('em_prebooking_summaries.id', '116')
        ->where('em_booking_payment_details.installment_no', '1')
        ->where('em_booking_payment_details.status', '1')
        ->get();

        foreach ($expiredBookings as $booking) {

            $booking_details = EventBookingPaymentDetail::where('em_booking_payment_summaries_id', $booking->booking_summary_id)->get();

            $booking_summary = EventBookingSummary::find($booking->booking_summary_id)
                ->update([
                    'booking_summaries_status' => 'rejected',
                    'booking_summaries_status_remarks' => 'auto reject due to payment'
                ]);

            // Cancel the booking by updating its status
            $pre_booking_summary = EventPreBookingSummary::find($booking->pre_booking_id);
            $pre_booking_summary->pre_booking_summary_status_id = '3';
            $pre_booking_summary->save();
        }

        $this->info('Expired bookings cancelled successfully.');
        \Log::info("Expired bookings cancelled successfully.");
    }
}
