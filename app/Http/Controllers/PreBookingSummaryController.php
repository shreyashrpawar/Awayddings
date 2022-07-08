<?php

namespace App\Http\Controllers;

use App\Models\BookingDetail;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\BookingSummary;
use App\Models\PreBookingSummary;
use App\Models\PreBookingSummaryStatus;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PreBookingSummaryController extends Controller
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
        
        $q = PreBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        
        $booking_summary = $q->get();
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

        $status = $request->selected_status;
        $selected_status = $request->selected_status;
        $pre_booking_id = $request->pre_booking_id;
        $admin_remarks = $request->admin_remark;

        $current_status      = PreBookingSummaryStatus::find($selected_status);
        $pre_booking_summary = PreBookingSummary::find($pre_booking_id);


        if($current_status->name == 'approved'){
            // create a record in the booking
            $additional_discount = $request->additional_discount ?? '0';
            $installments        = $request->installments;
            $total_amount = $pre_booking_summary->total_amount - $additional_discount;
            $booking_data = [
                'user_id' => $pre_booking_summary->user_id,
                'pre_booking_summary_id' => $pre_booking_summary->id,
                'property_id' => $pre_booking_summary->property_id,
                'check_in' => $pre_booking_summary->check_in,
                'check_out' => $pre_booking_summary->check_out,
                'amount' => $pre_booking_summary->total_amount,
                'discount' => $additional_discount,
                'total_amount' => $total_amount,
                'pax' => $pre_booking_summary->pax,
                'admin_remarks' =>$admin_remarks,
                'status' => 1
            ];


            $installment_details = $this->calculateInstallments($pre_booking_summary,$installments,$total_amount);

            // booking
            $booking_summary =  Bookingsummary::create($booking_data);
            foreach($pre_booking_summary->pre_booking_details as $key => $val){

                $booking_details =  BookingDetail::create([
                    'booking_summaries_id' => $booking_summary->id,
                    'date' => Carbon::parse($val->date),
                    'hotel_chargable_type_id' => $val->hotel_chargable_type_id,
                    'rate' => $val->rate,
                    'qty' => $val->qty,
                    'threshold' => $val->threshold
                ]);
            }

            // booking details

            // booking payment
            $booking_payment = [
                'booking_summaries_id' => $booking_summary->id,
                'installment_no' => $installments,
                'amount' => $total_amount,
                'discount' => $additional_discount,
                'paid' => 0,
                'due' => $total_amount,
                'status' => 1
            ];

            $booking_payments = BookingPaymentSummary::create($booking_payment);

            // booking payment installment

            for($i = 0; $i < count($installment_details); $i++){
                $date = $installment_details[$i]['date'];
                $installment_amount = $installment_details[$i]['installment_amount'];

                $booking_temp = [
                    'booking_payment_summaries_id' => $booking_payments->id,
                    'date' => Carbon::parse($date),
                    'amount' => $installment_amount,
                    'installment_no' => $i+1,
                    'status' => 1
                ];

                $booking_payment_details = BookingPaymentDetail::create($booking_temp);
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

    public function calculateInstallments($pre_booking_summary,$installment_count,$total_amount){
            $today = Carbon::now();
            $check_in = Carbon::parse($pre_booking_summary->check_in);
            $diff = $check_in->diffInDays($today);

            $installment_days = ceil($diff/$installment_count);

            $installment_amount = $total_amount/$installment_count;
            $temp_date = Carbon::now();
            $details = [];
            for($i = 1;$i <= $installment_count; $i++){
               $temp_date_1         = $temp_date;
               $installment_amount =   $installment_amount;
               array_push($details,[
                   'date' => $temp_date_1->addDays($installment_days)->format('d-m-Y'),
                   'installment_amount' => $installment_amount
               ]);
            }
            return $details;
    }
}
