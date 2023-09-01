<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventBookingResource;
use App\Http\Resources\EventManagementResource;
use App\Http\Resources\EventPrebookingResource;
use App\Http\Resources\EventResource;
use App\Models\Artist;
use App\Models\ArtistPerson;
use App\Models\Decoration;
use App\Models\EmAddonFacility;
use App\Models\EmAddonFacilityDetails;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventBookingSummary;
use App\Models\LightandSound;
use App\Models\Location;
use App\Models\TimeSlot;
use App\Models\EventPreBookingSummary;
use App\Models\EventPreBookingDetails;
use App\Models\EventPreBookingAddsonDetails;
use App\Models\EventPreBookingAddsonArtist;
use App\Models\PreBookingSummary;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use PDF;
use Storage;
// use App\Jobs\generateEventSummaryPdf;
use App\Jobs\GenerateEventSummaryPdfJob;

class EventManagementController extends Controller
{
    public function event_details(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $pending_summary = PreBookingSummary::where('user_id', $user_id)
        ->where('status', 1)
        ->whereIn('pre_booking_summary_status_id', [1, 2])
        ->whereDate('check_in', '>=', Carbon::now())
        ->first();

        $event = Event::with(['decorations','artists'])->where('status', 1)->get();

        $additional_facility = EmAddonFacility::where('status', 1)->get();

        $additional_artist = Artist::doesntHave('events')->orderBy('id', 'DESC')->get();

        $data =[
            'event' => $event,
            'additional_facility' =>$additional_facility,
            'additional_artist' => $additional_artist,
            'prefilled_data' => $pending_summary
        ];

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => new EventManagementResource($data)
        ]);
    }

    public function submit_em_data(Request $request)
    {

        // print_r($request->all()); exit;
        $this->validate($request,[
            'property_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'pax' => 'required',
            'total_amount' => 'required',
            'events' => 'present|array',
            'events.*.date' => 'required|string',
            'events.*.event_id' => 'required',
            //'events.*.artist_person_id' => 'required', optional
            'events.*.decor_person_id' => 'required',
            'events.*.start_time' => 'required|string',
            'events.*.end_time' => 'required|string',
            'addition.addson_facilities' => 'array',
            //'addition.additional_artist' => 'present|array', optional
            //'addition.addson_facilities.*.em_addon_facility_id' => 'required', optional
            //'addition.addson_facilities.*.em_addon_facility_details_id' => 'required', optional
            //'addition.additional_artist.artist_id' => 'required', optional
            //'addition.additional_artist.artist_details_id' => 'required', optional

        ]);

        $user = auth()->user();
        $user_id = $user->id;
        $property_id = $request->property_id;
        $user_budget = $request->total_amount;
        $check_in_date = Carbon::parse($request->start_date);
        $check_out_date = Carbon::parse($request->end_date);
        $total_amount = $request->total_amount;

        $adults = $request->pax;
        $events =$request->input('events');
        $addition = $request->input('addition');
        // $addition = json_decode(json_encode($request->input('addition')), true);;
        $user_remark = $request->remarks;

        $bride_name = $request->bride_name;
        $groom_name = $request->groom_name;

        $groupedPdfData = [];
        $pdfData = [];
        $facilityData = [];
        $dateWiseEventCounts = [];

        DB::beginTransaction();

        $temp_data = [
            'user_id' => $user_id,
            'property_id' => $property_id,
            'check_in' => $check_in_date,
            'check_out' => $check_out_date,
            'total_amount' => $total_amount,
            'budget' => $total_amount,
            'user_remarks' => $user_remark,
            'bride_name' => $bride_name,
            'groom_name' => $groom_name,
            'status' => 1,
            'pax' => $adults,
            'pre_booking_summary_status_id' => 1,
        ];
        // $prebooking = DB::table('pre_booking_summaries')->insert($temp_data);

        try {
            $pre_booking_summary = EventPreBookingSummary::create($temp_data);


            $basicDetails = [
                'title' => 'Welcome to Awayddings',
                'prebooking_id' => $pre_booking_summary->id,
                'user_name' => $pre_booking_summary->user->name,
                'user_phone' => $pre_booking_summary->user->phone,
                'property_name' => $pre_booking_summary->property->name,
                'adult' => $pre_booking_summary->pax,
                'duration' => $pre_booking_summary->check_in->format('d-m-Y') . ' - ' . $pre_booking_summary->check_out->format('d-m-Y'),
                'amount' => $pre_booking_summary->total_amount,
            ];
            
            foreach ($events as $key => $val) {
                $date = $val['date'];
                // print_r($date);
                $artist_amount = 0;
                $decor_amount = 0;
                $total_amount = 0;
                if (isset($val['artist_person_id'])) {

                    $artist_person = ArtistPerson::where('id', $val['artist_person_id'])->first();
                    $artist_amount = $artist_person['price'];
                    $artist = $artist_person['name'];
                    $total_amount = $artist_amount;
                    // $artist_image_url = ($val->artistPerson->image ?  $val->artistPerson->image->url : null);
                } 
                if ($val['decor_person_id']) {
                    $decor_details = Decoration::where('id', $val['decor_person_id'])->first();
                    $decor_amount = $decor_details['price'];
                    $decor = $decor_details['name'];
                    $total_amount = $decor_amount;
                    // $decor_image_url = ($val->decoration->image ? $val->decoration->image->url : null);

                    // $decor_amount = Decoration::where('id', $val['decor_person_id'])->pluck('price')->first();
                    // $total_amount = $decor_amount;
                }
                if(isset($val['artist_person_id']) && isset($val['decor_person_id'])) {
                    $total_amount = $artist_amount + $decor_amount;
                }
                $date = Carbon::parse($date);
                // print_r($val['decor_person_id']); exit;
                // $artist_amount = 
                $temp_data = [
                    'date' => Carbon::parse($date),
                    'em_prebooking_summaries_id' => $pre_booking_summary->id,
                    'em_event_id' => $val['event_id'],
                    'start_time' => $val['start_time'],
                    'end_time' => $val['end_time'],
                    'em_artist_person_id' => $val['artist_person_id'] !== "" ? $val['artist_person_id'] : null,
                    'em_decor_id' => $val['decor_person_id'] !== "" ? $val['decor_person_id'] : null,
                    'artist_amount' => $artist_amount,
                    'decor_amount' => $decor_amount,
                    'total_amount' => $total_amount,
                ];
                try {
                    $prebookingdetails = EventPreBookingDetails::create($temp_data);
                    $dateString = $date->format('Y-m-d');
                    $groupedPdfData[$dateString][] = [
                        'details_id' => $prebookingdetails->id,
                        'event' => $prebookingdetails->events->name,
                        'date' => $date,
                        'time' => $val['start_time'] . ' - ' . $val['end_time'],
                        'artist' => $artist,
                        'decor' => $decor,
                        'artist_amount' => $artist_amount,
                        'decor_amount' => $decor_amount,
                        'start_time' => $val['start_time'],
                        'end_time' => $val['end_time'],
                        'decor_image_url' => ($prebookingdetails->decoration && $prebookingdetails->decoration->image ? $prebookingdetails->decoration->image->url : null),
                        'artist_image_url' => ($prebookingdetails->artistPerson && $prebookingdetails->artistPerson->image ? $prebookingdetails->artistPerson->image->url : null),

                    ];
                    // print_r($groupedPdfData);
                } catch (Throwable $e) {
                    print_r($temp_data);
                    return $e;
                }
            }
            // Handle addson_facilities
            if (isset($addition['addson_facilities']) && is_array($addition['addson_facilities'])) {
                foreach ($addition['addson_facilities'] as $facility) {
                    $em_addon_facility_id = $facility['em_addon_facility_id'];
                    $em_addon_facility_details_id = $facility['em_addon_facility_details_id'];
                    $facility_details = EmAddonFacilityDetails::where('id', $em_addon_facility_details_id)->first();
                    $amount = $facility_details['price'];

                    $temp_data = [
                        'em_prebooking_summaries_id' => $pre_booking_summary->id,
                        'em_addon_facility_id' => $em_addon_facility_id,
                        'facility_details_id' => $em_addon_facility_details_id,
                        'total_amount' => $amount,
                    ];
                    try {
                        $addson_details = EventPreBookingAddsonDetails::create($temp_data);
                        $facilityData[] = [
                            'facility_id' => $addson_details->id,
                            'facility' => $facility_details['name'],
                            'facility_description' => $facility_details['description'],
                            'amount' => $amount,
                            'facility_image_url' => ($addson_details->addson_facility_details->image ? $addson_details->addson_facility_details->image->url : null),
                            // Add other relevant fields here
                        ];
                        // print_r($facilityData); exit;
                    } catch (Throwable $e) {
                        print_r($temp_data);
                        return $e;
                    }

                    // Process and store the facility data as needed
                }
            }

            // Handle additional_artist
            if (isset($addition['additional_artist'])) {
                $additional_artist = $addition['additional_artist'];
                $artist_id = $additional_artist['artist_id'];
                $add_artist = Artist::where('id', $artist_id)->first();
                $artist_name = $add_artist['name'];

                $artist_person_id = $additional_artist['artist_details_id'];
                $add_artist_person = ArtistPerson::where('id', $artist_person_id)->first();
                $add_artist_person_amount = $add_artist_person['price'];

                $temp_data = [
                    'em_prebooking_summaries_id' => $pre_booking_summary->id,
                    'em_addson_artist_id' => $artist_id,
                    'em_addson_artist_person_id' => $artist_person_id,
                    'addson_artist_amount' => $artist_amount,
                    'total_amount' => $add_artist_person_amount,
                ];

                try {
                    $addson_artist = EventPreBookingAddsonArtist::create($temp_data);
                    $pdfData[] = [
                        'additional_id' => $addson_artist->id,
                        'artist_person' => $artist_person,
                        'artist' => $artist_name,
                        'amount' => $add_artist_person_amount,
                        'artist_person_image_url' => ($addson_artist->addson_artist_person->image ? $addson_artist->addson_artist_person->image->url : null ),
                        'artist_image_url' => ($addson_artist->addson_artist->image ? $addson_artist->addson_artist->image->url : null ),
                    ];
                    // print_r($pdfData); exit;
                } catch (Throwable $e) {
                    print_r($temp_data);
                    return $e;
                }
                // Now you can use $artist_id and $artist_details_id as needed
            }

            DB::commit();
            // $details = ['data' => $pre_booking_summary->id];
            // generateEventSummaryPdf::dispatch($details);
            $job = new GenerateEventSummaryPdfJob($basicDetails, $groupedPdfData, $facilityData, $pdfData);
            dispatch($job);
            return response()->json([
                'success' => true,
                'message' => 'Data successfully inserted',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    // public function generatePDF($prebookingid)
    // {
    //     $summary = EventPreBookingSummary::with([
    //         'user',
    //         'property',
    //         'event_pre_booking_details',
    //         'pre_booking_summary_status',
    //         'event_pre_booking_details.artistPerson',
    //         'event_pre_booking_addson_details',
    //         'event_pre_booking_addson_artist_person',
    //     ])->find($prebookingid);

    //     $basicDetails = [
    //         'title' => 'Welcome to Awayddings',
    //         'prebooking_id' => $summary->id,
    //         'user_name' => $summary->user->name,
    //         'user_phone' => $summary->user->phone,
    //         'property_name' => $summary->property->name,
    //         'adult' => $summary->pax,
    //         'duration' => $summary->check_in->format('d-m-Y') . ' - ' . $summary->check_out->format('d-m-Y'),
    //         'amount' => $summary->total_amount,
    //     ];

    //     $groupedPdfData = [];
    //     $dateWiseEventCounts = [];

    //     foreach ($summary->event_pre_booking_details as $val) {

    //         $decor = '';
    //         $artist = '';
    //         $artist_image_url = '';
    //         $decor_image_url = '';
    //         $artist_amount = 0;
    //         $decor_amount = 0;

    //         if ($val->artistPerson) {
    //             $artist_image_url = ($val->artistPerson->image ? asset('storage/' . $val->artistPerson->image->url) : null);
    //             $artist =  $val->artistPerson->name;
    //             $artist_amount = $val->artist_amount;
    //         } elseif ($val->decoration) {
    //             $decor_image_url = ($val->decoration->image ? asset('storage/' . $val->decoration->image->url) : null);
    //             $decor =  $val->decoration->name;
    //             $decor_amount = $val->decor_amount;
    //         }
    //         // dd($decor_amount);

    //         $groupedPdfData[$val->date][] = [
    //             'details_id' => $val->id,
    //             'event' => $val->events->name,
    //             'date' => $val->date,
    //             'time' => $val->start_time . ' - ' . $val->end_time,
    //             'artist' => $artist,
    //             'decor' => $decor,
    //             'artist_amount' => $artist_amount,
    //             'decor_amount' => $decor_amount,
    //             'start_time' => $val->start_time,
    //             'end_time' => $val->end_time,
    //             'decor_image_url' => $decor_image_url,
    //             'artist_image_url' => $artist_image_url,
    //         ];
    //     }

    //     foreach($summary->event_pre_booking_addson_details as $key => $val) {
    //         $particular = '';
    //         $image_url = '';
    //         $data_name = '';
    //         $amount = $val->total_amount;
    //         $data_name = 'facility';
    //         $image_url = ($val->addson_facility_details->image ? $val->addson_facility_details->image->url : null);
    //         // elseif ($val->addson_artist_person) {
    //         //     $particular = $val->addson_artist_person->name;
    //         //     $amount = $val->artist_amount;
    //         // }
    //         // dd($val->addson_facility_details);
            
    //         $facilityData[] = [
    //             'facility_id' => $val->id,
    //             'facility' => $val->addson_facility->name,
    //             'facility_description' => $val->addson_facility_details->description,
    //             'amount' => $val->addson_facility_details->price,
    //             'facility_image_url' => $image_url,
    //             // Add other relevant fields here
    //         ];
            
    //     }

    //     foreach($summary->event_pre_booking_addson_artist_person as $key => $val) {
    //         // dd($val);
    //         $artist_person = '';
    //         $artist = '';
    //         $image_url = '';
    //         $data_name = '';
    //         $amount = $val->addson_artist_amount;
    //         if ($val->addson_artist_person) {
    //             $artist_person = $val->addson_artist_person->name;
    //             $artist_person_image_url = ($val->addson_artist_person->image ? asset('storage/' . $val->addson_artist_person->image->url) : null );
    //             $data_name = 'additionalArtistPerson';
    //         }
    //         $artistParticular = '';
    
    //         if ($val->addson_artist) {
    //             $artist = $val->addson_artist->name;
    //             $artist_image_url = ($val->addson_artist->image ? $val->addson_artist->image->url : null );
    //             $data_name = 'additionalArtist';
    //         }
            
    //         $pdfData[] = [
    //             'additional_id' => $val->id,
    //             'artist_person' => $artist_person,
    //             'artist' => $artist,
    //             'amount' => $amount,
    //             'artist_person_image_url' => $artist_person_image_url,
    //             'artist_image_url' => $artist_image_url,
    //                 ];
            
    //     }
    //     $additional_data = array_merge($facilityData,$pdfData);
    //     // dd($additional_data);

    //     $pdf = PDF::loadView('PDF.myPDF', [
    //         'basicDetails' => $basicDetails,
    //         'additional_data' => $additional_data,
    //         'groupedPdfData' => $groupedPdfData
    //     ]);
    
    //     return $pdf->output();
    // }

    public function get_property_with_location(Request $request)
    {
        $data = Location::with('property:location_id,id,name')->where('status', '1')->get(['id','name']);
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    

    public function get_event_bookings_history(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $pending_summary = EventPreBookingSummary::with(['user', 'pre_booking_summary_status', 'property', 'event_pre_booking_details', 'event_pre_booking_addson_details', 'event_pre_booking_addson_artist_person' ])
            ->where('user_id', $user_id)->where('pre_booking_summary_status_id', 1)->orderBy('created_at', 'desc')->get();

        $cancelled_summary = EventPreBookingSummary::with(['user', 'pre_booking_summary_status', 'property', 'event_pre_booking_details', 'event_pre_booking_addson_details', 'event_pre_booking_addson_artist_person' ])
        ->where('user_id', $user_id)->whereIn('pre_booking_summary_status_id',[3,4])->orderBy('created_at', 'desc')->get();

        $approved_summary = EventBookingSummary::with(['user', 'booking_details','bookingAddsonDetails', 'property', 'booking_payment_summary','bookingAddsonArtistPerson', 'booking_payment_summary.booking_payment_details'])
            ->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => [
               'pending' =>  EventPrebookingResource::collection($pending_summary),
               'cancelled' => EventPrebookingResource::collection($cancelled_summary),
               'approved' => EventBookingResource::collection($approved_summary),
               'completed' => []
            ],
        ]);
    }

}
