<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelChargableType;
use App\Models\Property;
use App\Models\PropertyAmenities;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyMedia;
use App\Models\PropertyRate;
use App\Models\PropertyRoomInclusion;
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
    public function propertyDetails(Request  $request,$id)
    {
        $properties = Property::join('locations','locations.id','=','properties.location_id')
                                ->join('property_default_rates','property_default_rates.property_id','=','properties.id')
                                ->where('properties.status',1)
                                ->where('properties.id',$id)
                                ->select('properties.id',
                                    'properties.name',
                                    'properties.featured_image as cover_image',
                                    'locations.name as location',
                                    'locations.id as location_id',
                                    'properties.gmap_embedded_code',
                                    'properties.description',
                                    )
                                ->inRandomOrder()
                                ->first();


        $properties_images= PropertyMedia::with('MediaSubCategory')
                                           ->where('media_category_id',1)
                                          ->whereIn('media_sub_category_id',[1,2,3,6,7,8,9,10,11,12,13])
                                           ->where('property_id',$id)
                                           ->get();

        $wedding_images= PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id',1)
            ->whereIn('media_sub_category_id',[4])
            ->where('property_id',$id)
            ->get();

        $wedding_video = PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id',2)
            ->whereIn('media_sub_category_id',[5])
            ->where('property_id',$id)
            ->get();

        $properties_amenities = PropertyAmenities::where('property_id',$id)
                            ->get();

        $properties_room_inclusion = PropertyRoomInclusion::where('property_id',$id)
                                                        ->get();


        $property_images = [];
        $property_wedding_images = [];
        $property_wedding_video = '';
        $property_amenities = [];
        $property_room_inclusions = [];
        $property_menus = [];

        foreach($properties_images as $key => $val){
            $temp_data =[
                'category' => $val->MediaSubCategory->name,
                'url' => $val->media_url,
            ];
           array_push($property_images,$temp_data);
        }
        foreach($wedding_images as $key => $val){
            $temp_data = [
                'category' => $val->MediaSubCategory->name,
                'url' => $val->media_url,
            ];
            array_push($property_wedding_images,$temp_data);
        }
        foreach($wedding_video as $key => $val){
            $property_wedding_video = $val->media_url;
        }

        foreach($properties_amenities as $key => $val){
            $temp_data =[
                'id' => $val->id,
                'name' => $val->hotel_facility->name,
            ];
            array_push($property_amenities,$temp_data);
        }
        foreach($properties_room_inclusion as $key => $val){
            $temp_data =[
                'id' => $val->id,
                'name' => $val->room_inclusion->name,
            ];
            array_push($property_room_inclusions,$temp_data);
        }

        foreach($wedding_video as $key => $val){
            $property_wedding_video = $val->media_url;
        }

        $properties->images = $property_images;
        $properties->wedding_images = $property_wedding_images;
        $properties->wedding_video  = $property_wedding_video;
        $properties->amenities  = $property_amenities;
        $properties->room_inclusion  = $property_room_inclusions;

        //
        $properties->double_occupancy_rate  = PropertyDefaultRate::where('property_id',$id)
                                                ->where('hotel_charagable_type_id',1)
                                                ->first()->amount;
        $properties->triple_occupancy_rate  = PropertyDefaultRate::where('property_id',$id)
                                                ->where('hotel_charagable_type_id',2)
                                                ->first()->amount;


        $other_properties = Property::select('id','name','featured_image')->whereNotIn('id',[$id])
            ->inRandomOrder()
            ->limit(3)
            ->where('location_id',$properties->location_id)->get();


        $properties->other_properties  = $other_properties;

        return response()->json([
            'success'=>true,
            'message' => 'SUCCESS',
            'data' => $properties
        ]);

    }
}
