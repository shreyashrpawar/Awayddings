<?php

namespace App\Http\Controllers;

use App\Jobs\SendInstallmentEmail;
use App\Jobs\SendCongratsEmail;
use App\Jobs\SendEmailToHotel;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\BookingSummary;
use Illuminate\Http\Request;
use PDF;
use Storage;
use App\Jobs\GeneratePDF;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use App\Models\User;
use App\Models\Property;
use App\Models\Vendor;

class BookingSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        
        $q = BookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        $bookings = $q->get();
        return view('app.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookingSummary  $bookingSummary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookings = BookingSummary::find($id);
        // dd($bookings);

        return view('app.bookings.show', compact('bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingSummary  $bookingSummary
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingSummary $bookingSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingSummary  $bookingSummary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingSummary $bookingSummary)
    {
        $bookingPaymentDetail = BookingPaymentDetail::where('id', $request->booking_payment_details_id)->where('installment_no', $request->installment_no)->first();
        if ($bookingPaymentDetail) {
            if ($bookingPaymentDetail->status == '1') {
                $bookingPaymentSummary = BookingPaymentSummary::where('id', $bookingPaymentDetail->booking_payment_summaries_id)->first();

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

                    $bookings = BookingSummary::find($bookingPaymentSummary->booking_summaries_id);
                    $user = User::find($bookings->user_id);
                    $property_id =  VendorPropertyAlignment::where('property_id',$bookings->property_id)->pluck('vendor_id')->first();
                    if ($property_id) { 
                        $vendor_email = Vendor::where('id', $property_id)->pluck('email')->first();
                        $emailDetails = ['vendor_email' => $vendor_email,'email' => $request->user_email, 'name' => $user->name, 'phone' => $user->phone, 'check_in' => $bookings->check_in, 'check_out' => $bookings->check_out, 'adult' => $bookings->pax,'booking_id' => $bookings->id];
                        SendEmailToHotel::dispatch($emailDetails);
                    }
                }

                if ($next_installment_date == null) {
                    $bookings = BookingSummary::find($bookingPaymentSummary->booking_summaries_id);
                    $details = ['data' => $bookings,'mailData' => $mailDetails];
                    GeneratePDF::dispatch($details);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingSummary  $bookingSummary
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingSummary $bookingSummary)
    {
        //
    }

    private function installmentMail($details)
    {
        SendInstallmentEmail::dispatch($details);
        return true;
    }

    private function generateInvoicePDF($bookingPaymentSummaryId)
    {
        $bookings = BookingSummary::find($bookingPaymentSummaryId);
        $pdf = PDF::loadView('pdf.customer-invoice', compact('bookings'));
       // Storage::put('public/pdf/customer_invoice_' . $bookingPaymentSummaryId . '.pdf', $pdf->output());
        $filePath = 'invoice/customer_invoice_' . $bookingPaymentSummaryId . '.pdf';
        Storage::disk('s3')->put($filePath, $pdf->output(), 'public');
        dd(Storage::disk('s3')->url($filePath));
        return true;
    }
}
