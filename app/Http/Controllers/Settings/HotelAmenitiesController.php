<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\HotelFacility;
use App\Models\Location;
use App\Models\PropertyAmenities;
use App\Models\RoomInclusion;
use Illuminate\Http\Request;

class HotelAmenitiesController extends Controller
{
    public function index(){
        $locations = HotelFacility::paginate(50);
        return view('app.settings.amenities.list',compact('locations'));
    }
    public function show($id,Request  $request){
        $data = HotelFacility::find($id);
        return view('app.settings.amenities.show',compact('data'));
    }
    public function edit(Request  $request,$id){
        $data = HotelFacility::find($id);
        return view('app.settings.amenities.edit',compact('data'));
    }
    public function store(Request  $request){
        $request->validate([
            'name' => ['required','unique:hotel_facilities','max:100'],
            'description' => ['required']
        ]);
        $name    = $request->name;
       HotelFacility::create([
            'name' => $name
        ]);
        $request->session()->flash('success','Successfully Saved');
        return redirect(route('property-amenities.index'));
    }
    public function create(Request  $request){
        return view('app.settings.amenities.create');
    }
    public function update(Request  $request,$id){
        $hotel_facility = HotelFacility::find($id);
        $request->validate([
            'name' => 'required|unique:hotel_facilities,name,'.$id,
            'status' => 'required|integer'
        ]);

        $name = $request->name;
        $location = $hotel_facility->update([
            'name' => $name,
            'status' => $request->status,
        ]);


        $request->session()->flash('success','Successfully Saved');
        return redirect(route('property-amenities.index'));


        return view('app.settings.locations.index');
    }

    public function getAllAmenitiesRoomInclusion(){
        $hotel_facility = HotelFacility::where('status',1)->get();
        $room_inclusions = RoomInclusion::where('status',1)->get();

        return response()->json([
             "success" => true,
             "data" => [
                         'amenities' => $hotel_facility,
                         'room_inclusions' => $room_inclusions
                        ],
        ]);
    }
}
