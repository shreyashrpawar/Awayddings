<?php

namespace App\Http\Controllers;

use App\Jobs\SendInstallmentEmail;
use App\Jobs\SendCongratsEmail;
use App\Jobs\SendEmailToHotel;
use App\Models\EventBookingPaymentDetail;
use App\Models\EventBookingPaymentSummary;
use App\Models\EventBookingSummary;
use Illuminate\Http\Request;
use PDF;
use Storage;
use App\Jobs\EventGeneratePDF;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use App\Models\User;
use App\Models\Property;
use App\Models\Vendor;

class EventBookingSummaryController extends Controller
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        
        $q = EventBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        $bookings = $q->get();
        return view('app.event_bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $bookings = EventBookingSummary::find($id);
        // dd($bookings);

        return view('app.event_bookings.show', compact('bookings'));
    }

    public function update(Request $request, EventBookingSummary $bookingSummary)
    {
        $bookingPaymentDetail = EventBookingPaymentDetail::where('id', $request->booking_payment_details_id)->where('installment_no', $request->installment_no)->first();
        if ($bookingPaymentDetail) {
            if ($bookingPaymentDetail->status == '1') {
                $bookingPaymentSummary = EventBookingPaymentSummary::where('id', $bookingPaymentDetail->em_booking_payment_summaries_id)->first();
        
                $total_paid = $bookingPaymentSummary->paid + round($bookingPaymentDetail->amount, 2);
                $total_due = round($bookingPaymentSummary->amount, 2) - $total_paid;

                $bookingPaymentSummary->paid = $total_paid;
                $bookingPaymentSummary->due = round($total_due, 2);
                $bookingPaymentSummary->save();

                $bookingPaymentDetail->status = 2;

                $remarks = $request->remarks;
                $next_installment_date = $request->next_installment_date;
                $installment_no = $request->installment_no;

                $installment_amount = $bookingPaymentDetail->amount;
                $mailDetails = ['email' => $request->user_email, 'installment_no' => $installment_no, 'total_paid' => $total_paid, 'total_due' => $total_due, 'installment_amount' => $installment_amount, 'next_installment_date' => $next_installment_date, 'remarks' => $remarks];

                if($installment_no == 1) {
                    // SendCongratsEmail::dispatch($details);
                    $details = ['email' => $request->user_email,'mailbtnLink' => '', 'mailBtnText' => '',
                    'mailTitle' => 'Congrats!', 'mailSubTitle' => 'Hooray! Your booking is confirmed.', 'mailBody' => 'We are happy to inform you that your booking is confirmed! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
                    SendCongratsEmail::dispatch($details);

                    $bookings = EventBookingSummary::find($bookingPaymentSummary->em_booking_summaries_id);
                    $user = User::find($bookings->user_id);
                    $property_id =  VendorPropertyAlignment::where('property_id',$bookings->property_id)->pluck('vendor_id')->first();
                    if ($property_id) { 
                        $vendor_email = Vendor::where('id', $property_id)->pluck('email')->first();
                        $emailDetails = ['vendor_email' => $vendor_email,'email' => $request->user_email, 'name' => $user->name, 'phone' => $user->phone, 'check_in' => $bookings->check_in, 'check_out' => $bookings->check_out, 'adult' => $bookings->pax,'booking_id' => $bookings->id];
                        SendEmailToHotel::dispatch($emailDetails);
                    }
                }

                if ($next_installment_date == null) {
                    $bookings = EventBookingSummary::find($bookingPaymentSummary->em_booking_summaries_id);
                    $details = ['data' => $bookings,'mailData' => $mailDetails];
                    EventGeneratePDF::dispatch($details);
                    //$pdf = $this->generateInvoicePDF($bookingPaymentSummary->booking_summaries_id);
                }else{
                    $this->installmentMail($mailDetails);
                }
            }
            $bookingPaymentDetail->payment_mode = $request->payment_mode;
            $bookingPaymentDetail->remarks = $request->remarks;
            $bookingPaymentDetail->save();

            return back()->with('success', 'Status updated successfully.');
        } else {
            return back()->with('error', 'Installment not found.');
        }
    }

    private function installmentMail($details)
    {
        SendInstallmentEmail::dispatch($details);
        return true;
    }
}
