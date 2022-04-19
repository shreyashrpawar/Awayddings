<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyRate;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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


        $hotel_chargable_type_id = $request->hotel_chargable_type_id;
        $property_id = $request->property_id;
        $amount = $request->amount;
        $date = $request->date;
        $type = $request->type;
//        $available =  $request->available;
//        $occupancy = $request->occupancy;



        $propertyDefaultRate = PropertyDefaultRate::where('property_id', $request->property_id)
                                                  ->where('hotel_charagable_type_id',$hotel_chargable_type_id)
                                                 ->first();


        if($type == 'amount')
        {
            if($request->id){
                $oldPurchaseEntry = PropertyRate::find($request->id);
                $oldPurchaseEntry->update([
                    'amount' => $amount
                ]);
            }else{
                 PropertyRate::create([
                    'property_id' => $property_id,
                    'hotel_chargable_type_id' => $propertyDefaultRate->hotel_charagable_type_id,
                    'amount' => $amount,
                    'date' => $date,
                    'available' => $propertyDefaultRate->qty,
                    'occupancy_percentage' =>$propertyDefaultRate->chargable_percentage
                ]);
            }
        }

//        if($type == 'available'){
//            if($request->id){
//                $oldPurchaseEntry = PropertyRate::find($request->id);
//                $data =  $oldPurchaseEntry->update([
//                    'amount' =>$oldPurchaseEntry->amount,
//                    'date' => $oldPurchaseEntry->date,
//                    'available' => $available,
//                    'occupancy_percentage' => $oldPurchaseEntry->chargable_percentage
//                ]);
//            }else{
//                $data = PropertyRate::create([
//                    'property_id' => $property_id,
//                    'hotel_chargable_type_id' => $propertyDefaultRate->hotel_charagable_type_id,
//                    'amount' => $propertyDefaultRate->amount,
//                    'date' => $date,
//                    'available' => $available,
//                    'occupancy_percentage' =>$propertyDefaultRate->chargable_percentage
//                ]);
//            }
//        }
//
//        if($type == 'occupancy'){
//            if($request->id){
//                $oldPurchaseEntry = PropertyRate::find($request->id);
//                $data =  $oldPurchaseEntry->update([
//                    'amount' =>$oldPurchaseEntry->amount,
//                    'date' => $oldPurchaseEntry->date,
//                    'available' =>  $oldPurchaseEntry->available,
//                    'occupancy_percentage' => $occupancy
//                ]);
//            }else{
//                $data = PropertyRate::create([
//                    'property_id' => $property_id,
//                    'hotel_chargable_type_id' => $propertyDefaultRate->hotel_charagable_type_id,
//                    'amount' => $propertyDefaultRate->amount,
//                    'date' => $date,
//                    'available' => $propertyDefaultRate->qty,
//                    'occupancy_percentage' =>$occupancy
//                ]);
//            }
//        }
//
        return response()->json([
            'success' =>  true,
            'message' => 'successfully saved'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PropertyRate  $propertyRate
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request  $request)
    {

        $user = $request->user();
        $roles = $user->getRoleNames();
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            VendorPropertyAlignment::where('property_id',$id)
                ->where('vendor_id',$vendor_id)
                ->firstOrfail();
        }
        $start_date  = Carbon::now()->subDays(7)->startOfDay();
        $end_date    = Carbon::now()->endOfDay();

        if($request->has('start_date') && $request->start_date != ''){
            $start_date = Carbon::parse($request->start_date)->startOfDay();
        }
        if($request->has('end_date') && $request->end_date != '' ){
            $end_date = Carbon::parse($request->end_date)->endOfDay();
        }

        $dateRange              = CarbonPeriod::create($start_date, $end_date);
        $property_default_rates = PropertyDefaultRate::where('property_id',$id)
                                    ->whereIn('hotel_charagable_type_id',[1,2])
                                    ->get();

        $property_rates = [];
        foreach($dateRange as $key => $date)
        {
            foreach($property_default_rates as $key => $default_rate)
            {
                $property_rate = PropertyRate::where('property_id',$id)
                                            ->where('hotel_chargable_type_id',$default_rate->hotel_charagable_type_id)
                                            ->whereDate('date',$date)
                                            ->first();

                if($property_rate)
                {
                    // push to the result array
                    $temp_data = collect([
                        'id' => $property_rate->id,
                        'property_id' =>$id,
                        'hotel_chargable_type_id' => $property_rate->hotel_chargable_type,
                        'amount' => $property_rate->amount,
                        'available' => $property_rate->available,
                        'date' => $property_rate->date,
                        'occupancy' => $property_rate->occupancy_percentage
                    ]);
                    array_push($property_rates,$temp_data);
                }else{
                    // take default price and push to the array.

                    $temp_data = collect([
                        'id' => null,
                        'property_id' =>$id,
                        'hotel_chargable_type_id' => $default_rate->hotel_charagable_type,
                        'amount' => $default_rate->amount,
                        'date' => $date,
                        'available' => $default_rate->qty,
                        'occupancy' => $default_rate->chargable_percentage
                    ]);
                    array_push($property_rates,$temp_data);
                }
            }
        }

        return view('app.property-rate.property-rate-show',compact('property_rates','start_date','end_date'));
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
