<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventManagementResource;
use App\Http\Resources\EventResource;
use App\Models\Artist;
use App\Models\EmAddonFacility;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\LightandSound;
use App\Models\Location;
use App\Models\TimeSlot;

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
            'events.*.artist_person_id' => 'required',
            'events.*.decor_person_id' => 'required',
            'events.*.start_time' => 'required|string',
            'events.*.end_time' => 'required|string',
            'addition.addson_facilities' => 'present|array',
            'addition.additional_artist' => 'present|array',
            'addition.addson_facilities.*.em_addon_facility_id' => 'required',
            'addition.addson_facilities.*.em_addon_facility_details_id' => 'required',
            'addition.additional_artist.artist_id' => 'required',
            'addition.additional_artist.artist_details_id' => 'required',

       ]);
        return response()->json([
            'success' => true,
            'message' => 'Data successfully inserted',
        ]);
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
