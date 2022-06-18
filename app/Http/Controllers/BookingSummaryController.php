<?php

namespace App\Http\Controllers;

use App\Jobs\SendInstallmentEmail;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\BookingSummary;
use Illuminate\Http\Request;

class BookingSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $bookings = BookingSummary::get();
       return view('app.bookings.index',compact('bookings'));
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

        return view('app.bookings.show',compact('bookings'));
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
        $bookingPaymentDetail = BookingPaymentDetail::where('id',$request->booking_payment_details_id)->where('installment_no',$request->installment_no)->first();
        if($bookingPaymentDetail){
            if($bookingPaymentDetail->status=='1'){                
                $bookingPaymentSummary = BookingPaymentSummary::where('id',$bookingPaymentDetail->booking_payment_summaries_id)->first();

                $total_paid = $bookingPaymentSummary->paid + round($bookingPaymentDetail->amount,2);
                $total_due = round($bookingPaymentSummary->amount,2) - $total_paid;

                $bookingPaymentSummary->paid = $total_paid;
                $bookingPaymentSummary->due = round($total_due,2);
                $bookingPaymentSummary->save();

                $bookingPaymentDetail->status = 2;

                $remarks = $request->remarks;
                $next_installment_date = $request->next_installment_date;
                $installment_no = $request->installment_no;
               
                $installment_amount = $bookingPaymentDetail->amount;
                $this->installmentMail($request->user_email,$installment_no,$total_paid,$total_due,$installment_amount,$next_installment_date,$remarks);
            }
            $bookingPaymentDetail->payment_mode = $request->payment_mode;
            $bookingPaymentDetail->remarks = $request->remarks;
            $bookingPaymentDetail->save();
            return back()->with('success', 'Status updated successfully.');
        }else{
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

    private function installmentMail($user_email,$installment_no,$total_paid,$total_due,$installment_amount,$next_installment_date,$remarks){
        $details = ['email' => $user_email,'installment_no' => $installment_no,'total_paid' => $total_paid,'total_due' => $total_due,'installment_amount' => $installment_amount,'next_installment_date' => $next_installment_date,'remarks' => $remarks];
        SendInstallmentEmail::dispatch($details);
        return true;
    }
}
