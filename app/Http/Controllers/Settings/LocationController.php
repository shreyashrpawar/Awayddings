<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
   public function index(){
       $locations = Location::paginate(50);
       return view('app.settings.locations.list',compact('locations'));
   }
    public function show(){
        return view('app.settings.locations.index');
    }
    public function edit(Request  $request,$id){
        $location = Location::find($id);
        return view('app.settings.locations.index',compact('location'));
    }
    public function store(Request  $request){
       $request->validate([
           'name' => ['required','unique:locations','max:100'],
           'description' => ['required']
       ]);

        $name = $request->name;
        $description = $request->description;
        $location = Location::create([
            'name' => $name,
            'description' => $description
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
