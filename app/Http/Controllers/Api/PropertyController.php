<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelChargableType;
use App\Models\Property;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyMedia;
use App\Models\PropertyRate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
  public function getRandomProperty(Request  $request){
      $adults = 50;
      $now = Carbon::now();
      $double_room_count = $adults/2;
      $double_occupancy_details = HotelChargableType::where('name','Double Occupancy Room')->where('status',1)->first();


      $properties = Property::join('locations','locations.id','=','properties.location_id')
                      ->join('property_default_rates','property_default_rates.property_id','=','properties.id')
                      ->where('properties.status',1)
                      ->where('property_default_rates.hotel_charagable_type_id',$double_occupancy_details->id)
                      ->where('property_default_rates.qty','>=',$double_room_count)
                      ->select('properties.id','properties.name','properties.featured_image','properties.description','locations.name as location',
                      'property_default_rates.amount','property_default_rates.qty')
                      ->inRandomOrder()
                      ->limit(3)
                      ->get();
      // get double occupancy price for today
      // room availability
      foreach($properties as $key => $property)
      {
        $custom_rate_double_occupancy =  PropertyRate::where('property_id',$property->id)
                                        ->whereDate('date',$now)
                                        ->where('hotel_chargable_type_id',$double_occupancy_details->id)->first();
        if(!$custom_rate_double_occupancy)
            $property->amount = $property->amount * $double_room_count;
       else
            $property->amount = $custom_rate_double_occupancy->amount * $double_room_count;

      }

      return response()->json([
          'success'=>true,
          'message' => 'Successfully Saved',
          'data' => $properties
      ]);
  }
  public function searchProperty(Request  $request){
        $adults          =  $request->adults;
        $location_id     =  $request->location_id;
        $start_date      =  $request->start_date;
        $end_date        =  $request->end_date;
        $budget          =  $request->budget;

        $double_room_count = $adults/2;

        $double_occupancy_details = HotelChargableType::where('name','Double Occupancy Room')->where('status',1)->first();


        $properties = Property::join('locations','locations.id','=','properties.location_id')
                                ->join('property_default_rates','property_default_rates.property_id','=','properties.id')
                                ->where('properties.status',$location_id)
                                ->where('properties.location_id',1)
                                ->where('property_default_rates.hotel_charagable_type_id',$double_occupancy_details->id)
                                ->where('property_default_rates.qty','>=',$double_room_count)
                                ->select('properties.id','properties.name','properties.featured_image','properties.description','locations.name as location',
                                    'property_default_rates.amount','property_default_rates.qty')
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();
        // get double occupancy price for today
        // room availability
        foreach($properties as $key => $property)
        {
            $custom_rate_double_occupancy =  PropertyRate::where('property_id',$property->id)
                ->where('hotel_chargable_type_id',$double_occupancy_details->id)->first();
            if(!$custom_rate_double_occupancy)
                $property->amount = $property->amount * $double_room_count;
            else
                $property->amount = $custom_rate_double_occupancy->amount * $double_room_count;

        }

        return response()->json([
            'success'=>true,
            'message' => 'Successfully Saved',
            'data' => $properties
        ]);
    }
}
