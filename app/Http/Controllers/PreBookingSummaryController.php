<?php

namespace App\Http\Controllers;

use App\Models\PreBookingSummary;
use App\Models\PreBookingSummaryStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PreBookingSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_summary = PreBookingSummary::orderby('id','DESC')->paginate(50);
        return view('app.prebooking.index',compact('booking_summary'));
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
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $summary = PreBookingSummary::with(['user','property','pre_booking_details','pre_booking_details.hotel_chargable_type','pre_booking_summary_status'])
            ->find($id);
        $pre_booking_summary_status = PreBookingSummaryStatus::pluck('name','id')->all();
       return view('app.prebooking.show',compact('summary','pre_booking_summary_status'));
//       return response()->json([
//           'success' => true,
//           'message' => 'Success',
//           'data' => $summary
//       ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function edit(PreBookingSummary $preBookingSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreBookingSummary $preBookingSummary)
    {
        return $request->all();
        $status = $request->selected_status;
        $pre_booking_id = $request->pre_booking_id;
        $amount = $request->final_amount;
        $admin_remarks = $request->admin_remark;

        $current_status      = PreBookingSummaryStatus::find($pre_booking_id);
        $pre_booking_summary = PreBookingSummary::find($pre_booking_id);
        if($current_status == 'approved'){
            // create a record in the booking
            $additional_discount = $request->additional_discount;
            $installments        = $request->installments;
            $total_amount = $pre_booking_summary->total_amount - $additional_discount;
            $booking_data = [
                'user_id' => $pre_booking_summary->user_id,
                'pre_booking_summary_id' => $pre_booking_id->id,
                'property_id' => $pre_booking_summary->property_id,
                'check_in' => $pre_booking_summary->check_in_date,
                'check_out' => $pre_booking_summary->check_out_date,
                'amount' => $pre_booking_summary->total_amount,
                'discount' => $additional_discount,
                'total_amount' => $total_amount,
                'pax' => $pre_booking_summary->adults,
                'admin_remarks' =>$admin_remarks,
                'status' => 1
            ];
            $installment_details = $this->calculateInstallments($pre_booking_summary,$installments,$total_amount);
            return $installment_details;
            // booking
            // booking details

            // booking payment
            $booking_payment_details = [
                'booking_id' => $booking->id,
                'installment_no' => $installments,
                'amount' => $total_amount,
                'status' => 1
            ];
            // booking payment installment
            for($i = 1; $i <= count($installment_details); $i++){
                $date = $installment_details[$i]['date'];
                $installment_amount = $installment_details[$i]['installment_amount'];

                $booking_temp = [
                    'date' => $date,
                    'amount' => $installment_amount,
                    'booking_id' => $booking_id,
                    'status' => 1
                ];
            }
            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));
        }else{
            // update on the existing pre booking
            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));

        }








       return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreBookingSummary $preBookingSummary)
    {
        //
    }

    private function calculateInstallments($pre_booking_summary,$installment_count,$total_amount){
            $today = Carbon::now();
            $check_in = Carbon::parse($pre_booking_summary->check_in);
            $diff = $check_in->diffInDays($today);
            $installment_days = $diff/$installment_count;
            $installment_amount = $total_amount/$installment_count;
            $temp_date = Carbon::now();
            $details = [];
            for($i = 1;$i <= $installment_count; $i++){
               $date               =  $temp_date->addDays($installment_days);
               $installment_amount =   $installment_amount;
               array_push($details,[
                   'date' => $date,
                   'installment_amount' => $installment_amount
               ]);
            }
            return $details;
    }
}
