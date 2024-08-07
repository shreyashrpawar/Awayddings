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
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyRate;
use App\Models\PreBookingDetails;
use Illuminate\Http\Request;
use App\Mail\ApprovalEmail;
use App\Mail\RejectionMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendCongratsEmail;
use App\Mail\BookingCancelEmail;
use App\Models\EventPreBookingSummary;
use DB;

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

        $eventPrebookings = EventPreBookingSummary::where('user_id', $summary->user->id)->get();

        $vanueCheckIn = $summary->check_in;
        $vanueCheckOut = $summary->check_out;

        // Initialize an array to store matching event prebooking IDs
        $firstMatchingEventPrebookingId = '';

        // Check if the check-in date of the current prebooking falls within the duration of any existing event prebooking
        foreach ($eventPrebookings as $eventPrebooking) {
            $eventCheckIn = $eventPrebooking->check_in;
            $eventCheckOut = $eventPrebooking->check_out;

            if ($vanueCheckIn >= $eventCheckIn && $eventCheckIn <= $vanueCheckOut) {
                // The check-in date of the current prebooking falls within the duration of an existing event prebooking
                // Add the matching event prebooking ID to the array
                $firstMatchingEventPrebookingId = $eventPrebooking->id;
                break;
            }
        }
        // dd($matchingEventPrebookingIds);
        
        
        $pre_booking_summary_status = PreBookingSummaryStatus::pluck('name','id')->all();
       return view('app.prebooking.show',compact('summary','pre_booking_summary_status', 'firstMatchingEventPrebookingId'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $summary = PreBookingSummary::with(['user','property','pre_booking_details','pre_booking_details.hotel_chargable_type','pre_booking_summary_status'])
            ->find($id);
        // $pre_booking_summary_status = PreBookingSummaryStatus::pluck('name','id')->all();
        // dd($summary);
       return view('app.prebooking.edit',compact('summary'));
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

        $user_details = User::find($pre_booking_summary->user_id);

        if($current_status->name == 'approved'){
            // create a record in the booking
            $additional_discount = $request->additional_discount ?? 0;
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
                'status' => 1,
                'booking_summaries_status' => 'approved',
                'booking_summaries_status_remarks' => 'APPROVED BY ADMIN',
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
                    'status' => 0
                ];

                $booking_payment_details = BookingPaymentDetail::create($booking_temp);
            }

            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $property_details = Property::find($pre_booking_summary->property_id);

            $details = [
                'name' => $user_details->name,
                'email' => $user_details->email,
                'phone' => $user_details->phone,
                'property_name' => $property_details->name,
                'check_in' => $pre_booking_summary->check_in,
                'check_out' => $pre_booking_summary->check_out,
                'adult' => $pre_booking_summary->pax,
                'amount' => $pre_booking_summary->total_amount,
                'discount' => $additional_discount,
                'total_amount' => $total_amount,
                'paid' => 0,
                'due' => $total_amount,
                'admin_remarks' =>$admin_remarks,
            ];

            $bookings = BookingSummary::find($booking_summary->id);

            Mail::to($user_details->email)->send(new ApprovalEmail($bookings));
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));
        }elseif($current_status->name == 'rejected'){
            // echo 'rejected'; exit;
            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            Mail::to($user_details->email)->send(new RejectionMail());

            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));
        }else if ($current_status->name == 'canceled'){

            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            Mail::to($user_details->email)->send(new BookingCancelEmail());

            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));
        }
        else{
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

    public function update_details(Request $request, PreBookingSummary $preBookingSummary)
    {
        $pre_booking_id = $request->pre_booking_id;
        $pre_booking_summary = PreBookingSummary::with(['user','property','pre_booking_details','pre_booking_details.hotel_chargable_type','pre_booking_summary_status'])
            ->find($pre_booking_id);
        $check_in = Carbon::parse($request->check_in);
        $check_out = Carbon::parse($request->check_out);
        $adults = $request->pax;

        $temp_checkout_date = $check_out->subDay();

        $nights = $check_in->diffInDays($check_out);
        $days = $nights + 1;
        $max_rooms = ceil($adults / 2);
        $min_rooms = ceil($adults / 3);

        $dateRange = CarbonPeriod::create($check_in, $temp_checkout_date);
        
        $property_id = $pre_booking_summary->property_id;
        $propertDetails = Property::find($pre_booking_summary->property_id);
        $property_chargable_items =
            PropertyDefaultRate::with('hotel_charagable_type')
                ->where('property_id', $property_id)
                ->where('amount', '>', 0)
                ->get();
        $previous_dates = array();
        $daterange_dates = array();
        
        foreach($pre_booking_summary->pre_booking_details as $k => $val) {
            array_push($previous_dates, $val->date);
        }

        $pre_booking_details = PreBookingDetails::where('pre_booking_summaries_id', $pre_booking_summary->id)->first();
        foreach ($dateRange as $date) {
            array_push($daterange_dates, $date);
            if (!in_array($date, $previous_dates))
            {
                $temp_data = [
                    'date' => $date->format('d-m-Y'),
                    'data' => []
                ];
                    $temp_data = [
                        'hotel_chargable_type_id' => $pre_booking_details->hotel_chargable_type_id,
                        'qty' => $pre_booking_details->qty,
                        'rate' => $pre_booking_details->rate,
                        'date' => Carbon::parse($date),
                        'threshold' => $pre_booking_details->threshold,
                        'pre_booking_summaries_id' => $pre_booking_summary->id,
                    ];
                    try {
                        PreBookingDetails::create($temp_data);
                    } catch (Throwable $e) {
                        print_r($temp_data);
                        return $e;
                    }
            }
        }

        foreach($pre_booking_summary->pre_booking_details as $k => $val) {
            if (!in_array($val->date, $daterange_dates))
            {
                PreBookingDetails::destroy($val->id);
            } 
        }

        $pre_booking_summary->update([
            'user_id' => $pre_booking_summary->user_id,
            'pre_booking_summary_id' => $request->id,
            'property_id' => $pre_booking_summary->property_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'pax' => $request->pax,
            'budget' => $request->budget,
            'status' => 1
        ]);
        return redirect(route('pre-bookings.show',$pre_booking_id));
    }
    
    public function update_qty_details(Request $request)
    {
        if ($request->ajax()) {
            PreBookingDetails::find($request->pk)
                ->update([
                    $request->name => $request->value
                ]);

                $preBookingDetails = PreBookingDetails::find($request->pk);
            if($request->name == 'rate') {
                $qty = $preBookingDetails->qty;
                $amount = $qty * $request->value;
            } else {
                $rate = $preBookingDetails->rate;
                $amount = $rate * $request->value;
            }

                $preBookingSummary = PreBookingSummary::find($preBookingDetails->pre_booking_summaries_id);
                // $preBookingDetails->update(['amount' => $result]);

                // $prebooking_allData = PreBookingDetails::where('pre_booking_summaries_id', $preBookingDetails->pre_booking_summaries_id)->get();

                // $result = PreBookingDetails::select('rate', 'qty', 'id')
                //     ->selectRaw('rate * qty as multiplication_result')
                //     ->where('pre_booking_summaries_id', $preBookingDetails->pre_booking_summaries_id)
                //     ->get();

                $result = PreBookingDetails::select(DB::raw('SUM(rate * qty) as total'))
                    ->where('pre_booking_summaries_id', $preBookingDetails->pre_booking_summaries_id)
                    ->first();
                
                    PreBookingSummary::find($preBookingDetails->pre_booking_summaries_id)
                    ->update([
                        'total_amount' => $result['total']
                    ]);

            // }
  
            return response()->json(['success' => true, 'total_amount' => $result['total'], 'amount' => $amount, 'this_id' => $request->pk ]);
        }
    }

    public function delete($id)
    {
        $preBookingDetails = PreBookingDetails::where('id', $id)->first();
        $preBookingSummary = PreBookingSummary::find($preBookingDetails->pre_booking_summaries_id);
        $reduceValue= $preBookingDetails->rate * $preBookingDetails->qty;
        $total_amount = $preBookingSummary->total_amount - $reduceValue;
        $preBookingSummary->update([
                        'total_amount' => $total_amount,
                    ]);
        PreBookingDetails::where('id', $id)->delete();

        return response()->json(['success' => true, 'total_amount' => $total_amount, 'message' => 'Data deleted successfully']);
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
