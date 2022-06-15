<?php

namespace App\Http\Controllers;

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
                $bookingPaymentSummary->paid = $bookingPaymentSummary->paid + $bookingPaymentDetail->amount;
                $bookingPaymentSummary->due = $bookingPaymentSummary->amount - $bookingPaymentSummary->paid;
                $bookingPaymentSummary->save();

                $bookingPaymentDetail->status = 2;
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
}
