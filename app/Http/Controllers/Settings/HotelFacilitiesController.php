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
    public function show(){
        return view('app.settings.locations.index');
    }
    public function edit(Request  $request,$id){
        $location = Location::find($id);
        return view('app.settings.locations.index',compact('location'));
    }
    public function store(Request  $request){
        $name = $request->name;
        $location = Location::create([
            'name' => $name
        ]);
        $request->session()->flash('success','Successfully Saved');
        return redirect(route('locations.index'));
    }
    public function create(Request  $request){
        return view('app.settings.locations.create');
    }
    public function update(Request  $request,$id){
        $location = Location::find($id);

        return view('app.settings.locations.index');
    }
}
