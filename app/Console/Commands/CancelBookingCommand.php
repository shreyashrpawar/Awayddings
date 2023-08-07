<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\BookingDetail;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\PreBookingSummaryStatus;
use App\Models\PreBookingSummary;
use App\Models\BookingSummary;

use Carbon\Carbon;

class CancelBookingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel';

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
        // return 0;
        // $expiredBookings = Booking::where('status', 'approved')
        //     ->where('created_at', '<=', Carbon::now()->subHours(48))
        //     ->whereNull('payment_status')
        //     ->get();

        $expiredBookings = DB::table('pre_booking_summaries')
        ->join('booking_summaries', 'pre_booking_summaries.id', '=', 'booking_summaries.pre_booking_summary_id')
        ->join('booking_payment_summaries', 'booking_summaries.id', '=', 'booking_payment_summaries.booking_summaries_id')
        ->join('booking_payment_details', 'booking_payment_details.booking_payment_summaries_id', '=', 'booking_payment_summaries.id')
        // ->select('booking_payment_details.*','pre_booking_summaries.id as pre_booking_id')
        ->select('booking_payment_details.*','pre_booking_summaries.id as pre_booking_id','booking_summaries.id as booking_summary_id')
        ->where('pre_booking_summaries.pre_booking_summary_status_id', 2)
        ->where('booking_payment_details.created_at', '<=', Carbon::now()->subHours(48))
        // ->where('pre_booking_summaries.id', '116')
        ->where('booking_payment_details.installment_no', '1')
        ->where('booking_payment_details.status', '1')
        ->get();

        foreach ($expiredBookings as $booking) {

            $booking_details = BookingDetail::where('booking_summaries_id', $booking->booking_summary_id)->get();

            // foreach($booking_details as $eachdetail) {
            //     $eachdetail->delete();
            // }
            // // $booking_details->delete();

            // $booking_payment_summary = BookingPaymentSummary::where('booking_summaries_id', $booking->booking_summary_id)->first();

            // $booking_payment_details = BookingPaymentDetail::where('booking_payment_summaries_id', $booking_payment_summary->id)->get();
            // foreach($booking_payment_details as $eachdetail) {
            //     $eachdetail->delete();
            // }
            // $booking_payment_summary->delete();

            $booking_summary = BookingSummary::find($booking->booking_summary_id)
                ->update([
                    'booking_summaries_status' => 'rejected',
                    'booking_summaries_status_remarks' => 'auto reject due to payment'
                ]);
                // dd($booking_summary);

            // Cancel the booking by updating its status
            $pre_booking_summary = PreBookingSummary::find($booking->pre_booking_id);
            $pre_booking_summary->pre_booking_summary_status_id = '3';
            $pre_booking_summary->save();
        }

        // foreach ($expiredBookings as $booking) {
        //     $pre_booking_summary = PreBookingSummary::find($booking->pre_booking_id);
        //     $pre_booking_summary->pre_booking_summary_status_id = '4';
        //     $pre_booking_summary->save();

        //     // $booking_summary = BookingSummary::destroy(1);
        // }

        $this->info('Expired bookings cancelled successfully.');
        \Log::info("Expired bookings cancelled successfully.");
    }
}
