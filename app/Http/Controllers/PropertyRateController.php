<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyRate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class PropertyRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $properties = Property::where('status',1)->get();
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
    public function show($id,Request  $request)
    {
        $start_date  = Carbon::now()->subDays(7)->startOfDay();
        $end_date    = Carbon::now()->endOfDay();

        if($request->has('start_date') && $request->start_date != ''){
            $start_date = Carbon::parse($request->start_date)->startOfDay();
        }

        if($request->has('end_date') && $request->end_date != '' ){
            $end_date = Carbon::parse($request->end_date)->endOfDay();
        }
        // get range of the dates.
        $dateRange = CarbonPeriod::create($start_date, $end_date);

        $property_default_rates = PropertyDefaultRate::where('property_id',$id)->get();

        $property_rates = [];
        foreach($dateRange as $key => $date)
        {
            foreach($property_default_rates as $key => $default_rate)
            {

                $property_rate = PropertyRate::where('property_id',$id)
                        ->where('hotel_chargable_type_id',$default_rate->hotel_charagable_type_id)
                        ->whereDate('date',$date)->first();

                if($property_rate){
                    // push to the result array
                    $temp_date = [
                        'hotel_chargable_type_id' => $property_rate->hotel_chargable_type_id,
                        'amount' => $property_rate->amount,
                        'date' => $property_rate->date,
                        'occupancy' => $property_rate->occupancy_percentage_
                    ];
                    array_push($property_rates,$temp_date);
                }else{
                    // take default price and push to the array.

                    $temp_date = [
                        'hotel_chargable_type_id' => $default_rate->hotel_charagable_type_id,
                        'amount' => $default_rate->amount,
                        'date' => $date,
                        'occupancy' => $default_rate->chargable_percentage
                    ];
                    array_push($property_rates,$temp_date);
                }
            }

        }
        return view('app.property-rate.property-rate-show',compact('property_rates'));
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
