<?php

namespace App\Http\Controllers;

use App\Models\HotelChargableType;
use App\Models\HotelFacility;
use App\Models\Location;
use App\Models\MediaSubCategory;
use App\Models\Property;
use App\Models\PropertyAmenities;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyMedia;
use App\Models\PropertyRoomInclusion;
use App\Models\RoomInclusion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $images_video_categories = MediaSubCategory::where('status',1)->where('media_category_id',1)->get();
        $menu_sub_categories     = MediaSubCategory::where('status',1)->where('media_category_id',3)->get();
        $hotel_facilities        = HotelFacility::where('status',1)->pluck('name','id')->all();
        $room_inclusions         = RoomInclusion::where('status',1)
                                                ->pluck('name','id')
                                                ->all();
        $hotel_chargable_type    = HotelChargableType::where('status',1)
                                                    ->pluck('name','id')
                                                    ->all();


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
//            dd($request->all());
            //dd($name);
           // dd(Str::snake($name,'_'));
            if($request->has(Str::snake($name,'_')))
            {
//                dd(Str::snake($name,'_').'_rate');
//                dd($request->all());
                //dd(Str::snake($name,'_'));
                $variable = Str::snake($name,'_');

                $propertyRate = [
                    'property_id' => $propertyDetails->id,
                    'hotel_charagable_type_id' => $id,
                    'amount' =>  $request[$variable.'_rate'],
                    'qty' =>  $request[$variable.'_count'],
                    'chargable_percentage' => $request[$variable.'_occupancy']
                ];


                PropertyDefaultRate::create($propertyRate);
            }
        }
        // amenities items of the property
        foreach($hotel_facilities as $id => $name){
            // dd($request->all());
            // dd(Str::snake($name,'_').'_amenities');
            if($request->has(Str::snake($name,'_').'_amenities'))
            {
                $amenities_data = [
                    'property_id' => $propertyDetails->id,
                    'hotel_facility_id' => $id
                ];
                // dd($amenities_data);
                try {
                    // Validate the value...
                    PropertyAmenities::create($amenities_data);
                } catch (Throwable $e) {

                    return false;
                }


            }
        }
        // room inclusion items of the property
        foreach($room_inclusions as $id => $name){
            if($request->has(Str::snake($name,'_').'_room_inclusion'))
            {
                $room_inclusion_details = [
                    'property_id' => $propertyDetails->id,
                    'hotel_facility_id' => $id
                ];
                try {
                    // Validate the value...
                    PropertyRoomInclusion::create($room_inclusion_details);
                } catch (Throwable $e) {
                    return false;
                }
              //  PropertyRoomInclusion::create($room_inclusion_details);
            }
        }
        foreach($images_video_categories as $key => $val){
           // dd($val);
            //dd(Str::snake($val->name,'_').'_upload');
            ;
            if ($request->hasFile(Str::snake($val->name,'_').'_upload')) {
                try {
                $file = $request[Str::snake($val->name,'_').'_upload'];
                $name = time() . $file->getClientOriginalName();

                $filePath = 'images/'.$propertyDetails->id.'/'.Str::snake($val->name,'_').'/'. $name;
                $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file));
                $s3_location = Storage::disk('s3')->url($filePath);
                PropertyMedia::create([
                    'property_id' => $propertyDetails->id,
                    'media_category_id' => $val->media_category_id,
                    'media_sub_category_id' => $val->id,
                    'media_url' =>$s3_location
                ]);
                } catch (Throwable $e) {
                    dd($e);
                    return false;
                }
            }
        }
        foreach($menu_sub_categories as $key => $val){

            if ($request->hasFile(Str::snake($val->name,'_').'_menu')) {
                $file = $request->file(Str::snake($val->name,'_').'_menu');
                $name = time() . $file->getClientOriginalName();
                $filePath = 'images/'.$propertyDetails->id.'/'.Str::snake($val->name,'_').'/'. $name;
                $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file));
                $s3_location = Storage::disk('s3')->url($filePath);
                PropertyMedia::create([
                    'property_id' => $propertyDetails->id,
                    'media_category_id' => $val->media_category_id,
                    'media_sub_category_id' => $val->id,
                    'media_url' =>$s3_location
                ]);
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
