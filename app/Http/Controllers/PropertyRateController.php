<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyRate;
use Illuminate\Http\Request;

class PropertyRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::where('status',1)->pluck('name','id')->all();
        return view('app.property-rate.list',compact('properties'));
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
     * @param  \App\Models\PropertyRate  $propertyRate
     * @return \Illuminate\Http\Response
     */
    public function show(PropertyRate $propertyRate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PropertyRate  $propertyRate
     * @return \Illuminate\Http\Response
     */
    public function edit(PropertyRate $propertyRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PropertyRate  $propertyRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropertyRate $propertyRate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PropertyRate  $propertyRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(PropertyRate $propertyRate)
    {
        //
    }
}
