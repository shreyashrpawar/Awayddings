<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventManagementResource;
use App\Http\Resources\EventResource;
use App\Models\Artist;
use App\Models\ArtistPerson;
use App\Models\Decoration;
use App\Models\EmAddonFacility;
use App\Models\EmAddonFacilityDetails;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\LightandSound;
use App\Models\Location;
use App\Models\TimeSlot;
use App\Models\EventPreBookingSummary;
use App\Models\EventPreBookingDetails;
use App\Models\EventPreBookingAddsonDetails;
use App\Models\EventPreBookingAddsonArtist;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class EventManagementController extends Controller
{
    public function event_details(Request $request)
    {
        $event = Event::with(['decorations','artists'])->where('status', 1)->get();

        $additional_facility = EmAddonFacility::where('status', 1)->get();

        $additional_artist = Artist::doesntHave('events')->orderBy('id', 'DESC')->get();

        $data =[
            'event' => $event,
            'additional_facility' =>$additional_facility,
            'additional_artist' => $additional_artist
        ];

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => new EventManagementResource($data)
        ]);
    }

    public function submit_em_data(Request $request)
    {
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

        DB::beginTransaction();

        $temp_data = [
            'user_id' => $user_id,
            'property_id' => $property_id,
            'check_in' => $check_in_date,
            'check_out' => $check_out_date,
            'total_amount' => $total_amount,
            'budget' => $user_budget,
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
            
            foreach ($events as $key => $val) {
                $date = $val['date'];
                // print_r($date);
                $artist_amount = 0;
                $decor_amount = 0;
                $total_amount = 0;
                if ($val['artist_person_id']) {

                    $artist_amount = ArtistPerson::where('id', $val['artist_person_id'])->pluck('price')->first();
                    $total_amount = $artist_amount;
                } elseif ($val['decor_person_id']) {
                    $decor_amount = Decoration::where('id', $val['decor_person_id'])->pluck('price')->first();
                    $total_amount = $decor_amount;
                }
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
                    EventPreBookingDetails::create($temp_data);
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
                    $amount = EmAddonFacilityDetails::where('id', $em_addon_facility_details_id)->pluck('price')->first();

                    $temp_data = [
                        'em_prebooking_summaries_id' => $pre_booking_summary->id,
                        'em_addon_facility_id' => $em_addon_facility_id,
                        'facility_details_id' => $em_addon_facility_details_id,
                        'total_amount' => $amount,
                    ];
                    try {
                        EventPreBookingAddsonDetails::create($temp_data);
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
                $artist_person_id = $additional_artist['artist_details_id'];
                $artist_amount = ArtistPerson::where('id', $artist_person_id)->pluck('price')->first();

                $temp_data = [
                    'em_prebooking_summaries_id' => $pre_booking_summary->id,
                    'em_addson_artist_id' => $artist_id,
                    'em_addson_artist_person_id' => $artist_person_id,
                    'addson_artist_amount' => $artist_amount,
                    'total_amount' => $artist_amount,
                ];

                try {
                    EventPreBookingAddsonArtist::create($temp_data);
                } catch (Throwable $e) {
                    print_r($temp_data);
                    return $e;
                }
                // Now you can use $artist_id and $artist_details_id as needed
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data successfully inserted',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function get_property_with_location(Request $request)
    {
        $data = Location::with('property:location_id,id,name')->where('status', '1')->get(['id','name']);
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    

    public function get_previous_propertry_booking_data(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data successfully inserted',
        ]);
    }

}
