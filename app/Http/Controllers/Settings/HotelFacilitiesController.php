<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\RoomInclusion;
use Illuminate\Http\Request;

class HotelFacilitiesController extends Controller
{
    public function index(){
        $locations = RoomInclusion::paginate(50);
        return view('app.settings.room_inclusion.list',compact('locations'));
    }

    public function show($id){
        $data = RoomInclusion::find($id);
        return view('app.settings.room_inclusion.show',compact('data'));
    }
    public function edit(Request  $request,$id){
        $data = RoomInclusion::find($id);
        return view('app.settings.room_inclusion.edit',compact('data'));
    }
    public function store(Request  $request){
        $request->validate([
            'name' => ['required','unique:room_inclusions','max:100']
        ]);
        $name = $request->name;
        $location = RoomInclusion::create([
            'name' => $name
        ]);
        $request->session()->flash('success','Successfully Saved');
        return redirect(route('property-room-inclusion.index'));
    }
    public function create(Request  $request){
        return view('app.settings.room_inclusion.create');
    }
    public function update(Request  $request,$id){
        $hotel_facility = RoomInclusion::find($id);
        $request->validate([
            'name' => 'required|unique:room_inclusions,name,'.$id,
            'status' => 'required|integer'
        ]);

        $name = $request->name;
        $location = $hotel_facility->update([
            'name' => $name,
            'status' => $request->status,
        ]);
        $request->session()->flash('success','Successfully Updated');
        return redirect(route('property-room-inclusion.index'));
    }
}
