<?php

namespace App\Http\Controllers;

use App\Models\EventPreBookingSummary;
use App\Models\EventPreBookingDetails;
use Illuminate\Http\Request;

use App\Models\EventBookingDetail;
use App\Models\EventBookingPaymentDetail;
use App\Models\EventBookingPaymentSummary;
use App\Models\EventBookingSummary;
use App\Models\EventPreBookingAddsonDetails;
use App\Models\EventPreBookingAddsonArtist;
use App\Models\EventBookingAddsonArtist;
use App\Models\EventBookingAddsonDetails;
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
use App\Mail\ApprovalEmail;
use App\Mail\RejectionMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendCongratsEmail;
use App\Mail\BookingCancelEmail;
use App\Jobs\SendApprovalEmail;
use DB;

class EventPreBookingSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        
        $q = EventPreBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        $pre_booking_summary = $q->get();
        // dd($pre_booking_summary);
        return view('app.event_prebooking.index',compact('pre_booking_summary'));
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
     * @param  \App\Models\EventPreBookingSummary  $eventPreBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $summary = EventPreBookingSummary::with([
            'user',
            'property',
            'event_pre_booking_details',
            'pre_booking_summary_status',
            'event_pre_booking_details.artistPerson',
            'event_pre_booking_addson_details',
            'event_pre_booking_addson_artist_person',
        ])->find($id);
        
        $total = 0;
        $key = 0; 
        $data = [];
        $event = '';
        foreach ($summary->event_pre_booking_details as $val) {
            $particular = '';
            $image_url = '';
            $data_name = '';
            $amount = 0;
            if ($val->artistPerson) {
                $image_url = ($val->artistPerson->image ? asset('storage/' . $val->artistPerson->image->url) : null );
                
                $particular = 'Artist Person - '.$val->artistPerson->name;
                $amount = $val->artist_amount;
                $image_url = $val->artistPerson;
                $data_name = 'artistPerson';
            } elseif ($val->decoration) {
                $image_url = ($val->decoration->image ? asset('storage/' . $val->decoration->image->url) : null );
                $particular = 'Decoration - '.$val->decoration->name;
                $amount = $val->decor_amount;
                $data_name = 'decor';
            }
            $event = $val->events->name;
            
            $data[] = [
                'id' => $val->id,
                'event' => $event,
                'date' => $val->date->format('d-m-Y'),
                'time' => $val->start_time . ' - ' . $val->end_time,
                'particular' => $particular,
                'data-name' => $data_name,
                'amount' => $amount,
                'image_url' => $image_url,
                // Add other relevant fields here
            ];
        }
        // dd($data);
        foreach($summary->event_pre_booking_addson_details as $key => $val) {
            $particular = '';
            $image_url = '';
            $data_name = '';
            $amount = $val->total_amount;
            if ($val->addson_facility) {
                $particular = 'Facility - '.$val->addson_facility->name;
            } elseif ($val->facility_details) {
                $particular = 'Facility Details - '.$val->facility_details->name;
            } 
            $data_name = 'facility';
            // elseif ($val->addson_artist_person) {
            //     $particular = $val->addson_artist_person->name;
            //     $amount = $val->artist_amount;
            // }
            
            $data[] = [
                'id' => $val->id,
                'event' => 'NA',
                'date' => 'NA', // You can set an empty date if it's not applicable
                'time' => 'NA', // You can set an empty time if it's not applicable
                'particular' => $particular,
                'data-name' => $data_name,
                'amount' => $amount,
                'image_url' => $image_url,
                // Add other relevant fields here
            ];
            
        }
        foreach($summary->event_pre_booking_addson_artist_person as $key => $val) {
            // dd($val);
            $particular = '';
            $image_url = '';
            $data_name = '';
            $amount = $val->addson_artist_amount;
            if ($val->addson_artist_person) {
                $particular = 'Additional Artist Person - '.$val->addson_artist_person->name;
                $image_url = ($val->addson_artist_person->image ? asset('storage/' . $val->addson_artist_person->image->url) : null );
                $data_name = 'additionalArtistPerson';
            }
            $artistParticular = '';
    
            if ($val->addson_artist) {
                $artistParticular = 'Additional Artist - '.$val->addson_artist->name;
                $image_url = ($val->addson_artist->image ? $val->addson_artist->image->url : null );
                $data_name = 'additionalArtist';
            }
            
            $data[] = [
                'id' => $val->id,
                'event' => 'NA',
                'date' => 'NA', // You can set an empty date if it's not applicable
                'time' => 'NA', // You can set an empty time if it's not applicable
                'particular' => $particular,
                'data-name' => $data_name,
                'amount' => $amount,
                'image_url' => $image_url,
                // Add other relevant fields here
            ];
            
        }
            
        $pre_booking_summary_status = PreBookingSummaryStatus::pluck('name','id')->all();
       return view('app.event_prebooking.show',compact('summary','pre_booking_summary_status', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EventPreBookingSummary  $eventPreBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $summary = EventPreBookingSummary::with([
            'user',
            'property',
            'event_pre_booking_details',
            'pre_booking_summary_status',
            'event_pre_booking_details.artistPerson',
            'event_pre_booking_addson_details',
            'event_pre_booking_addson_artist_person',
        ])->find($id);
       return view('app.event_prebooking.edit',compact('summary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventPreBookingSummary  $eventPreBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventPreBookingSummary $preBookingSummary)
    {
        // dd($request->all());

        $status = $request->selected_status;
        $selected_status = $request->selected_status;
        $pre_booking_id = $request->pre_booking_id;
        $admin_remarks = $request->admin_remark;

        $current_status      = PreBookingSummaryStatus::find($selected_status);
        $pre_booking_summary = EventPreBookingSummary::find($pre_booking_id);

        $user_details = User::find($pre_booking_summary->user_id);

        if($current_status->name == 'approved'){
            // create a record in the booking
            $additional_discount = $request->additional_discount ?? 0;
            $installments        = $request->installments;
            $total_amount = $pre_booking_summary->total_amount - $additional_discount;
            $booking_data = [
                'user_id' => $pre_booking_summary->user_id,
                'em_prebooking_summaries_id' => $pre_booking_summary->id,
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

            // booking summary
            $booking_summary =  EventBookingSummary::create($booking_data);
            foreach($pre_booking_summary->event_pre_booking_details as $key => $val){

                $booking_details =  EventBookingDetail::create([
                    'em_booking_summaries_id' => $booking_summary->id,
                    'em_event_id' => $val->em_event_id,
                    'date' => Carbon::parse($val->date),
                    'start_time' => $val->start_time,
                    'end_time' => $val->end_time,
                    'em_artist_person_id' => $val->em_artist_person_id,
                    'em_decor_id' => $val->em_decor_id,
                    'artist_amount' => $val->artist_amount,
                    'decor_amount' => $val->decor_amount,
                    'total_amount' => $val->total_amount,
                ]);
            }

            // booking details

            foreach($pre_booking_summary->event_pre_booking_addson_details as $key => $val) {

                $booking_details =  EventBookingAddsonDetails::create([
                    'em_booking_summaries_id' => $booking_summary->id,
                    'em_addon_facility_id' => $val->em_addon_facility_id,
                    'facility_details_id' => $val->facility_details_id,
                    'total_amount' => $val->total_amount,
                ]);
                
            }
            foreach($pre_booking_summary->event_pre_booking_addson_artist_person as $key => $val) {
                // dd($val);
                $booking_details =  EventBookingAddsonArtist::create([
                    'em_booking_summaries_id' => $booking_summary->id,
                    'em_addson_artist_id' => $val->em_addson_artist_id,
                    'em_addson_artist_person_id' => $val->em_addson_artist_person_id,
                    'addson_artist_amount' => $val->addson_artist_amount,
                    'total_amount' => $val->total_amount,
                ]);
                
            }

            // booking payment
            $booking_payment = [
                'em_booking_summaries_id' => $booking_summary->id,
                'installment_no' => $installments,
                'amount' => $total_amount,
                'discount' => $additional_discount,
                'paid' => 0,
                'due' => $total_amount,
                'status' => 1
            ];

            $booking_payments = EventBookingPaymentSummary::create($booking_payment);

            // booking payment installment

            for($i = 0; $i < count($installment_details); $i++){
                $date = $installment_details[$i]['date'];
                $installment_amount = $installment_details[$i]['installment_amount'];

                $booking_temp = [
                    'em_booking_payment_summaries_id' => $booking_payments->id,
                    'date' => Carbon::parse($date),
                    'amount' => $installment_amount,
                    'installment_no' => $i+1,
                    'status' => 1
                ];

                $booking_payment_details = EventBookingPaymentDetail::create($booking_temp);
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

            $bookings = EventBookingSummary::find($booking_summary->id);

            // Dispatch the SendApprovalEmail job to the queue
            SendApprovalEmail::dispatch($bookings);

            // Mail::to($user_details->email)->send(new ApprovalEmail($bookings));
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('event-pre-booking.index'));
        }elseif($current_status->name == 'rejected'){
            // echo 'rejected'; exit;
            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            Mail::to($user_details->email)->send(new RejectionMail());

            $request->session()->flash('success','Successfully Updated');
            return redirect(route('event-pre-booking.index'));
        }else if ($current_status->name == 'canceled'){

            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            Mail::to($user_details->email)->send(new BookingCancelEmail());

            $request->session()->flash('success','Successfully Updated');
            return redirect(route('event-pre-booking.index'));
        }
        else{
            // update on the existing pre booking
            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('event-pre-booking.index'));

        }

       return $request->all();
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

    public function update_details(Request $request, EventPreBookingSummary $preBookingSummary)
    {
        $event_pre_booking_id = $request->event_pre_booking_id;
        // $pre_booking_summary = EventPreBookingSummary::with(['user','property','pre_booking_details','pre_booking_details.hotel_chargable_type','pre_booking_summary_status'])
        //     ->find($pre_booking_id);
        $pre_booking_summary = EventPreBookingSummary::with([
            'user',
            'property',
            'event_pre_booking_details',
            'pre_booking_summary_status',
            'event_pre_booking_details.artistPerson',
            'event_pre_booking_addson_details',
            'event_pre_booking_addson_artist_person',
        ])->find($event_pre_booking_id);
        // dd($pre_booking_summary);
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
        
        foreach($pre_booking_summary->event_pre_booking_details as $k => $val) {
            array_push($previous_dates, $val->date);
        }

        $pre_booking_details = EventPreBookingDetails::where('em_prebooking_summaries_id', $pre_booking_summary->id)->first();
        foreach ($dateRange as $date) {
            array_push($daterange_dates, $date);
            if (!in_array($date, $previous_dates))
            {
                $temp_data = [
                    'date' => $date->format('d-m-Y'),
                    'data' => []
                ];
                    $temp_data = [
                        'em_event_id' => $pre_booking_details->em_event_id,
                        'date' => Carbon::parse($date),
                        'start_time' => $pre_booking_details->start_time,
                        'end_time' => $pre_booking_details->end_time,
                        'em_artist_person_id' => $pre_booking_details->em_artist_person_id,
                        'em_decor_id' => $pre_booking_details->em_decor_id,
                        'artist_amount' => $pre_booking_details->artist_amount,
                        'decor_amount' => $pre_booking_details->decor_amount,
                        'em_prebooking_summaries_id' => $pre_booking_summary->id,
                        'total_amount' => $pre_booking_details->total_amount,
                    ];
                    // dd($temp_data);
                    try {
                        EventPreBookingDetails::create($temp_data);
                    } catch (Throwable $e) {
                        print_r($temp_data);
                        return $e;
                    }
            }
        }

        foreach($pre_booking_summary->event_pre_booking_details as $k => $val) {
            if (!in_array($val->date, $daterange_dates))
            {
                EventPreBookingDetails::destroy($val->id);
            } 
        }

        $pre_booking_summary->update([
            'user_id' => $pre_booking_summary->user_id,
            // 'em_event_id' => $pre_booking_details->em_event_id,
            'em_prebooking_summaries_id' => $request->id,
            'property_id' => $pre_booking_summary->property_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'pax' => $request->pax,
            'budget' => $request->budget,
            'status' => 1
        ]);
        return redirect(route('event-pre-booking.show',$event_pre_booking_id));
    }

    public function update_qty_details(Request $request)
    {
        if ($request->ajax()) {

            $amount = $request->value;
            if($request->name == 'artistPerson') {
                $preBookingDetails = EventPreBookingDetails::find($request->pk);
                $summary_id = $preBookingDetails->em_prebooking_summaries_id;
                $previous_value = $preBookingDetails->artist_amount;
                $preBookingDetails->artist_amount = $request->value;
                $preBookingDetails->total_amount = $request->value;
            } else if($request->name == 'decor') {
                $preBookingDetails = EventPreBookingDetails::find($request->pk);
                $summary_id = $preBookingDetails->em_prebooking_summaries_id;
                $previous_value = $preBookingDetails->decor_amount;
                $preBookingDetails->decor_amount = $request->value;
                $preBookingDetails->total_amount = $request->value;
            } else if($request->name == 'facility') {
                $preBookingDetails = EventPreBookingAddsonDetails::find($request->pk);
                $summary_id = $preBookingDetails->em_prebooking_summaries_id;
                $preBookingDetails->total_amount = $request->value;
            }else if($request->name == 'additionalArtist') {
                $preBookingDetails = EventPreBookingAddsonArtist::find($request->pk);
                $summary_id = $preBookingDetails->em_prebooking_summaries_id;
                $preBookingDetails->addson_artist_amount = $request->value;
                $preBookingDetails->total_amount = $request->value;
            }

            $preBookingDetails->save();

            $summary = EventPreBookingSummary::with([
                'user',
                'property',
                'event_pre_booking_details',
                'pre_booking_summary_status',
                'event_pre_booking_details.artistPerson',
                'event_pre_booking_addson_details',
                'event_pre_booking_addson_artist_person',
            ])->find($summary_id);

            // Calculate the sum of total_amount from event_pre_booking_details
            $eventPreBookingDetailsTotal = $summary->event_pre_booking_details->sum('total_amount');

            // Calculate the sum of total_amount from event_pre_booking_addson_details
            $eventPreBookingAddsonDetailsTotal = $summary->event_pre_booking_addson_details->sum('total_amount');

            // Calculate the sum of total_amount from event_pre_booking_addson_artist_person
            $eventPreBookingAddsonArtistPersonTotal = $summary->event_pre_booking_addson_artist_person->sum('total_amount');

            // Calculate the grand total by adding up the above calculated sums
            $grandTotal = $eventPreBookingDetailsTotal + $eventPreBookingAddsonDetailsTotal + $eventPreBookingAddsonArtistPersonTotal;

            $summary->total_amount = $grandTotal;
            $summary->save();

                // $preBookingSummary = EventPreBookingSummary::find($preBookingDetails->em_prebooking_summaries_id);
                
  
            return response()->json(['success' => true, 'total_amount' => $grandTotal, 'amount' => $amount, 'this_id' => $request->pk ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventPreBookingSummary  $eventPreBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventPreBookingSummary $eventPreBookingSummary)
    {
        //
    }
}
