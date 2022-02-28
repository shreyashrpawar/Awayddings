<?php

namespace App\Http\Controllers;

use App\Models\HotelChargableType;
use App\Models\HotelFacility;
use App\Models\Location;
use App\Models\MediaSubCategory;
use App\Models\Property;
use App\Models\PropertyDefaultRate;
use App\Models\RoomInclusion;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.property.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $images_video_categories = MediaSubCategory::where('status',1)->where('media_category_id',1)->pluck('name','id')->all();
        $menu_sub_categories     = MediaSubCategory::where('status',1)->where('media_category_id',3)->pluck('name','id')->all();
        $hotel_facilities        = HotelFacility::where('status',1)->pluck('name','id')->all();
        $room_inclusions         = RoomInclusion::where('status',1)
                                            ->pluck('name','id')
                                            ->all();
        $hotel_chargable_type = HotelChargableType::where('status',1)
                            ->pluck('name','id')->all();

        $locations = Location::where('status',1)
                     ->pluck('name','id')->all();
        return view('app.property.create',compact('locations','images_video_categories','menu_sub_categories','hotel_facilities','room_inclusions','hotel_chargable_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $images_video_categories = MediaSubCategory::where('status',1)->where('media_category_id',1)->pluck('name','id')->all();
        $menu_sub_categories     = MediaSubCategory::where('status',1)->where('media_category_id',3)->pluck('name','id')->all();
        $hotel_facilities        = HotelFacility::where('status',1)->pluck('name','id')->all();
        $room_inclusions         = RoomInclusion::where('status',1)
                                                ->pluck('name','id')
                                                ->all();
        $hotel_chargable_type    = HotelChargableType::where('status',1)
                                                    ->pluck('name','id')
                                                    ->all();

        return $request->all();

        $property_basic_details = [
            'name' => $request->property_name,
            'description' => $request->property_description,
            'address' => $request->property_address,
            'alias_name' => $request->property_name,
            'location_id' => $request->property_location_id,
            'gmap_embedded_code' => $request->property_gmap_embedded_code,
            'featured_image' => $request->cover_image_upload,
            'status' => 0
        ];

        $propertyDetails = Property::create($property_basic_details);
        // chargable items of the property
        foreach($hotel_chargable_type as $id => $name){
            if($request->has(Str::snake($name,'_').'_room'))
            {
                $propertyRate = [
                    'property_id' => $propertyDetails->id,
                    'hotel_charagable_type_id' => $id,
                    'amount' =>  $request->Str::snake($name,'_').'_room_rate',
                    'qty' =>  $request->Str::snake($name,'_').'_room_count',
                    'chargable_percentage' => $request->Str::snake($name,'_').'_occupancy'
                ];
                PropertyDefaultRate::create($propertyRate);
            }
        }
        // amenities items of the property
        foreach($hotel_facilities as $id => $name){
            if($request->has(Str::snake($name,'_').'_amenities'))
            {
                $amenities_data = [
                    'property_id' => $propertyDetails->id,
                    'hotel_facility_id' => $id
                ];
                PropertyAmenities::create($amenities_data);
            }
        }

        // room inclusion items of the property
        foreach($room_inclusions as $id => $name){
            if($request->has(Str::snake($name,'_').'_room_inclusion'))
            {
                $room_inclusion_details = [
                    'property_id' => $propertyDetails->id,
                    'room_inclusion_id' => $id
                ];
                PropertyRoomInclusion::create($room_inclusion_details);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        //
    }
}
