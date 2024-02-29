<?php

namespace App\Http\Controllers;

use App\Models\BookingSummary;
use App\Models\Leads;
use App\Models\PreBookingSummary;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Illuminate\Support\Facades\DB;

use App\Models\BookingDetail;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\PreBookingSummaryStatus;
use App\Models\User;
use App\Jobs\SendEmailEmiPayments;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        $q = Property::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('id',$property_id);
        }
        $properties_count = $q->count();

        $pre_bookings = PreBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $pre_bookings->whereIn('property_id',$property_id);
        }
        $pre_bookings_count = $pre_bookings->count();

        $bookings = BookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $bookings->whereIn('property_id',$property_id);
        }
        $startDate = Carbon::now()->subMonth(12);
        $endDate = Carbon::now();
        $leads_count = Leads::select(DB::raw('count(*) as count, status'))
        ->whereNull('deleted_at')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('status')
        ->get();
        $bookings_count = $bookings->count();

        return view('home', compact('properties_count','pre_bookings_count','bookings_count', 'leads_count'));
    }

    public function test_emi_cron(Request $request)
    {
        $emis = BookingPaymentDetail::where('status', 1)->where('date', '<=', now()->addDay()) // Retrieve EMIs due within 24 hours
               ->where('email_sent', 0) // Add a flag to avoid sending duplicate emails
               ->orderBy('created_at', 'desc')
               ->get();
            //    dd($emis);
        foreach ($emis as $emi) {
            $emi->email_sent = 1;
            $emi->save(); 
            dd($emi->email_sent);
            $booking_payment = BookingPaymentSummary::find($emi->booking_payment_summaries_id);
            $bookings = BookingSummary::find($booking_payment->booking_summaries_id);
            $user = User::find($bookings->user_id);
            // $details = ['email' => $user->email, 'name' => $user->name, 'phone' => $user->phone, 'amount' => $emi->amount];
            $details = ['email' => $user->email,'mailbtnLink' => '', 'mailBtnText' => '',
                    'mailTitle' => 'Reminder For EMI Payments', 'mailSubTitle' => 'Reminder for EMI Payments from Awayddings.', 'mailBody' => 'I hope that you are well. I am contacting you on behalf of [your company] with regard to the following invoice:'.$emi->amount.'This invoice is due for payment on '.$emi->date.'. Please could you kindly confirm receipt of this invoice and advise as to whether payment has been scheduled.!'];
            // $details = ['data' => $bookings,'mailData' => $mailDetails];
            SendEmailEmiPayments::dispatch($details);
            // Send email to the user with the EMI payment reminder
            // Use Laravel's built-in Mail facade or any email library of your choice
            // You can create an appropriate email template and pass relevant data to it
            // For example:
            // Mail::to($emi->user->email)->send(new EmiReminderMail($emi));
            
            // Update the flag to mark the email as sent
            $emi->email_sent = true;
            $emi->save(); exit;
        }
    }

    public function test_cancel_booking_cron(Request $request){
        // $approveStatus = 'approve';

        $bookingPaymentDetails = DB::table('pre_booking_summaries')
            ->join('booking_summaries', 'pre_booking_summaries.id', '=', 'booking_summaries.pre_booking_summary_id')
            ->join('booking_payment_summaries', 'booking_summaries.id', '=', 'booking_payment_summaries.booking_summaries_id')
            ->join('booking_payment_details', 'booking_payment_details.booking_payment_summaries_id', '=', 'booking_payment_summaries.id')
            ->select('booking_payment_details.*','pre_booking_summaries.id as pre_booking_id','booking_summaries.id as booking_summary_id')
            ->where('pre_booking_summaries.pre_booking_summary_status_id', 2)
            // ->where('booking_payment_details.created_at', '<=', Carbon::now()->subHours(48))
            // ->where('pre_booking_summaries.id', '116')
            ->where('booking_payment_details.installment_no', '1')
            ->where('booking_payment_details.status', '1')
            ->get();
            // dd($bookingPaymentDetails);
            foreach ($bookingPaymentDetails as $booking) {

                $booking_details = BookingDetail::where('booking_summaries_id', $booking->booking_summary_id)->get();

                foreach($booking_details as $eachdetail) {
                    $eachdetail->delete();
                }
                // $booking_details->delete();

                $booking_payment_summary = BookingPaymentSummary::where('booking_summaries_id', $booking->booking_summary_id)->first();

                $booking_payment_details = BookingPaymentDetail::where('booking_payment_summaries_id', $booking_payment_summary->id)->get();
                foreach($booking_payment_details as $eachdetail) {
                    $eachdetail->delete();
                }
                $booking_payment_summary->delete();

                $booking_summary = BookingSummary::destroy($booking->booking_summary_id);
                    // dd($booking_summary);

                // Cancel the booking by updating its status
                $pre_booking_summary = PreBookingSummary::find($booking->pre_booking_id);
                $pre_booking_summary->pre_booking_summary_status_id = '3';
                $pre_booking_summary->save();

                echo 'deleted successfully'; exit;
            }

        // $bookingPaymentDetails = BookingPaymentDetail::whereHas('bookingSummary.preBooking', function ($query) use ($approveStatus) {
        //     $query->where('status', $approveStatus);
        // })->first();

        // if ($bookingPaymentDetails) {
        //     $paymentStatus = $bookingPaymentDetails->status;
        //     // Do something with the payment status
        // } else {
        //     // Handle case when no booking payment details found
        // }

    }
}
