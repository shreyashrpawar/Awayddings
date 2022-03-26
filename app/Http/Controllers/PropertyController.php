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
use Throwable;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::orderBy('id', 'DESC')->paginate(50);
        return view('app.property.list', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $images_video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 1)->pluck('name', 'id')->all();
        $video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 2)->pluck('name', 'id')->all();
        $menu_sub_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 3)->pluck('name', 'id')->all();
        $hotel_facilities = HotelFacility::where('status', 1)->pluck('name', 'id')->all();
        $room_inclusions = RoomInclusion::where('status', 1)
            ->pluck('name', 'id')
            ->all();
        $hotel_chargable_type = HotelChargableType::where('status', 1)
            ->pluck('name', 'id')->all();

        $locations = Location::where('status', 1)
            ->pluck('name', 'id')->all();
        return view('app.property.create', compact('video_categories', 'locations', 'images_video_categories', 'menu_sub_categories', 'hotel_facilities', 'room_inclusions', 'hotel_chargable_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $images_video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 1)->get();
        $video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 2)->pluck('name', 'id')->all();
        $menu_sub_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 3)->get();
        $hotel_facilities = HotelFacility::where('status', 1)->pluck('name', 'id')->all();
        $room_inclusions = RoomInclusion::where('status', 1)
            ->pluck('name', 'id')
            ->all();

        $hotel_chargable_type = HotelChargableType::where('status', 1)
            ->pluck('name', 'id')
            ->all();

//        if($request->hasFile('featured_image')){
//            $file = $request->featured_image;
//            $name = time() . $file->getClientOriginalName();
//            $filePath = 'images/property/'. $name;
//            $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
//            $featured_image_path = Storage::disk('s3')->url($filePath);
//        }


        $property_basic_details = [
            'name' => $request->property_name,
            'description' => $request->property_description,
            'address' => $request->property_address,
            'alias_name' => $request->property_name,
            'location_id' => $request->property_location_id,
            'gmap_embedded_code' => $request->property_gmap_embedded_code,
            'featured_image' => $request->featured_image,
            'status' => 0
        ];

        $propertyDetails = Property::create($property_basic_details);
        // chargable items of the property

        foreach ($hotel_chargable_type as $id => $name) {

            if ($request->has(Str::snake($name, '_'))) {
                $variable = Str::snake($name, '_');
                $propertyRate = [
                    'property_id' => $propertyDetails->id,
                    'hotel_charagable_type_id' => $id,
                    'amount' => $request[$variable . '_rate'],
                    'qty' => $request[$variable . '_count'],
                    'chargable_percentage' => $request[$variable . '_occupancy']
                ];
                PropertyDefaultRate::create($propertyRate);
            }
        }
        // amenities items of the property
        foreach ($hotel_facilities as $id => $name) {
            if ($request->has(Str::snake($name, '_') . '_amenities')) {
                $amenities_data = [
                    'property_id' => $propertyDetails->id,
                    'hotel_facility_id' => $id
                ];
                try {
                    PropertyAmenities::create($amenities_data);
                } catch (Throwable $e) {

                    return false;
                }
            }
        }
        // room inclusion items of the property
        foreach ($room_inclusions as $id => $name) {
            if ($request->has(Str::snake($name, '_') . '_room_inclusion')) {
                $room_inclusion_details = [
                    'property_id' => $propertyDetails->id,
                    'hotel_facility_id' => $id
                ];
                try {
                    PropertyRoomInclusion::create($room_inclusion_details);
                } catch (Throwable $e) {
                    return false;
                }
            }
        }
        foreach ($video_categories as $key => $val) {
            if ($request->has(Str::snake($val->name, '_') . '_upload')) {
                $files = $request[Str::snake($val->name, '_') . '_upload'];
                foreach ($files as $file) {
                    try {
                        PropertyMedia::create([
                            'property_id' => $propertyDetails->id,
                            'media_category_id' => $val->media_category_id,
                            'media_sub_category_id' => $val->id,
                            'media_url' => $file
                        ]);

                    } catch (Throwable $e) {
                        return false;
                    }
                }

            }
        }
        foreach ($images_video_categories as $key => $val) {
            if ($request->has(Str::snake($val->name, '_') . '_upload')) {
                $files = $request[Str::snake($val->name, '_') . '_upload'];
                foreach ($files as $file) {
                    try {

                        PropertyMedia::create([
                            'property_id' => $propertyDetails->id,
                            'media_category_id' => $val->media_category_id,
                            'media_sub_category_id' => $val->id,
                            'media_url' => $file
                        ]);
                    } catch (Throwable $e) {
                        return false;
                    }
                }

            }
        }
        foreach ($menu_sub_categories as $key => $val) {
            if ($request->has(Str::snake($val->name, '_') . '_menu')) {
                $file = $request->file(Str::snake($val->name, '_') . '_menu');

                PropertyMedia::create([
                    'property_id' => $propertyDetails->id,
                    'media_category_id' => $val->media_category_id,
                    'media_sub_category_id' => $val->id,
                    'media_url' => $file
                ]);
            }
        }


        if ($request->has(Str::snake($val->name, '_') . '_video')) {
            try {
                PropertyMedia::create([
                    'property_id' => $propertyDetails->id,
                    'media_category_id' => $val->media_category_id,
                    'media_sub_category_id' => $val->id,
                    'media_url' => $request[Str::snake($val->name, '_') . '_video']
                ]);
            } catch (Throwable $e) {
                report($e);
                return false;
            }
        }

        $request->session()->flash('success', 'Successfully Saved');

        return redirect(route('property.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Property::find($id);
        return view('app.property.show', compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        $images_video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 1)->pluck('name', 'id')->all();
        $video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 2)->pluck('name', 'id')->all();
        $menu_sub_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 3)->pluck('name', 'id')->all();
        $hotel_facilities = HotelFacility::where('status', 1)->pluck('name', 'id')->all();
        $room_inclusions = RoomInclusion::where('status', 1)
            ->pluck('name', 'id')
            ->all();
        $hotel_chargable_type = HotelChargableType::where('status', 1)
            ->pluck('name', 'id')->all();

        $locations = Location::where('status', 1)
            ->pluck('name', 'id')->all();

        $data = $property;

        return view('app.property.edit', compact('video_categories', 'locations', 'images_video_categories', 'menu_sub_categories', 'hotel_facilities', 'room_inclusions', 'hotel_chargable_type', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {


        $images_video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 1)->get();
        $menu_sub_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 3)->get();
        $video_categories = MediaSubCategory::where('status', 1)->where('media_category_id', 2)->get();

        $hotel_facilities = HotelFacility::where('status', 1)->pluck('name', 'id')->all();
        $room_inclusions = RoomInclusion::where('status', 1)
            ->pluck('name', 'id')
            ->all();

        $hotel_chargable_type = HotelChargableType::where('status', 1)
            ->pluck('name', 'id')
            ->all();


        $property_basic_details = [
            'name' => $request->property_name,
            'description' => $request->property_description,
            'address' => $request->property_address,
            'alias_name' => $request->property_name,
            'location_id' => $request->property_location_id,
            'gmap_embedded_code' => $request->property_gmap_embedded_code,
//          'status' => $request->status
        ];

        if ($request->hasFile('featured_image')) {
            $file = $request->featured_image;
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/property/' . $name;
            $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');
            $featured_image_path = Storage::disk('s3')->url($filePath);
            $property_basic_details['featured_image'] = $featured_image_path;
        }


        $propertyDetails = $property->update($property_basic_details);

        // room inclusion
        foreach ($room_inclusions as $id => $name) {

            $existing_room_inclusion = PropertyRoomInclusion::where('property_id', $property->id)
                ->where('hotel_facility_id', $id)
                ->first();

            if ($request->has(Str::snake($name, '_') . '_room_inclusion')) {

                if (!$existing_room_inclusion) {
                    $room_inclusion_details = [
                        'property_id' => $property->id,
                        'hotel_facility_id' => $id
                    ];
                    try {
                        PropertyRoomInclusion::create($room_inclusion_details);
                    } catch (Throwable $e) {
                        return false;
                    }
                }
            } else {

                if ($existing_room_inclusion) {
                    $existing_room_inclusion->delete();
                }

            }
        }
        // amenities
        foreach ($hotel_facilities as $id => $name) {
            $existing_room_amenities = PropertyAmenities::where('property_id', $property->id)
                ->where('hotel_facility_id', $id)
                ->first();
            if ($request->has(Str::snake($name, '_') . '_amenities')) {
                if (!$existing_room_amenities) {
                    $amenities_data = [
                        'property_id' => $property->id,
                        'hotel_facility_id' => $id
                    ];
                    try {
                        PropertyAmenities::create($amenities_data);
                    } catch (Throwable $e) {
                        return false;
                    }
                }
            } else {
                if ($existing_room_amenities) {
                    $existing_room_amenities->delete();
                }

            }
        }
        // chargable entities
        foreach ($hotel_chargable_type as $id => $name) {
            $PropertyDefaultRate = PropertyDefaultRate::where('property_id', $property->id)
                ->where('hotel_charagable_type_id', $id)->first();
            if ($request->has(Str::snake($name, '_'))) {
                $variable = Str::snake($name, '_');
                if ($PropertyDefaultRate) {
                    //  update
                    $propertyRate = [
                        'amount' => $request[$variable . '_rate'],
                        'qty' => $request[$variable . '_count'],
                        'chargable_percentage' => $request[$variable . '_occupancy']
                    ];
                    $PropertyDefaultRate->update($propertyRate);
                } else {
                    //create
                    $propertyRate = [
                        'property_id' => $property->id,
                        'hotel_charagable_type_id' => $id,
                        'amount' => $request[$variable . '_rate'],
                        'qty' => $request[$variable . '_count'],
                        'chargable_percentage' => $request[$variable . '_occupancy']
                    ];
                    PropertyDefaultRate::create($propertyRate);
                }
            } else {
                if ($PropertyDefaultRate) {
                    $PropertyDefaultRate->delete();
                }
            }
        }

        foreach ($images_video_categories as $key => $val) {
            if ($request->hasFile(Str::snake($val->name, '_') . '_upload')) {
                $files = $request[Str::snake($val->name, '_') . '_upload'];

                foreach ($files as $file) {
                    try {
                        $name = time() . $file->getClientOriginalName();

                        $filePath = 'images/' . $property->id . '/' . Str::snake($val->name, '_') . '/' . $name;
                        $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');
                        $s3_location = Storage::disk('s3')->url($filePath);
                        PropertyMedia::create([
                            'property_id' => $property->id,
                            'media_category_id' => $val->media_category_id,
                            'media_sub_category_id' => $val->id,
                            'media_url' => $s3_location
                        ]);
                    } catch (Throwable $e) {
                        dd($e);
                        return false;
                    }
                }

            }
        }
        foreach ($menu_sub_categories as $key => $val) {
            if ($request->hasFile(Str::snake($val->name, '_') . '_menu')) {
                $file = $request->file(Str::snake($val->name, '_') . '_menu');
                $name = time() . $file->getClientOriginalName();
                $filePath = 'images/' . $property->id . '/' . Str::snake($val->name, '_') . '/' . $name;
                $file_upload = Storage::disk('s3')->put($filePath, file_get_contents($file));
                $s3_location = Storage::disk('s3')->url($filePath);
                PropertyMedia::create([
                    'property_id' => $property->id,
                    'media_category_id' => $val->media_category_id,
                    'media_sub_category_id' => $val->id,
                    'media_url' => $s3_location
                ]);
            }
        }
        foreach ($video_categories as $key => $val) {
            $data = PropertyMedia::where('property_id', $property->id)
                ->where('media_category_id', $val->media_category_id)
                ->where('media_sub_category_id', $val->id)
                ->first();

            if ($request->has(Str::snake($val->name, '_') . '_video')) {
                if ($data) {
                    // update
                    $data->update([
                        'media_url' => $request[Str::snake($val->name, '_') . '_video']
                    ]);
                } else {
                    // create
                    try {
                        PropertyMedia::create([
                            'property_id' => $property->id,
                            'media_category_id' => $val->media_category_id,
                            'media_sub_category_id' => $val->id,
                            'media_url' => $request[Str::snake($val->name, '_') . '_video']
                        ]);
                    } catch (Throwable $e) {
                        report($e);
                        return false;
                    }
                }
            } else {
                if ($data) {
                    $data->delete();
                }
            }
        }
        $request->session()->flash('success', 'Successfully Updated');
        return redirect(route('property.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        //
    }

    public function deletePropertyMedia(Request $request)
    {
        $id = $request->id;
        $data = PropertyMedia::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully Deleted',
            'data' => $data
        ]);
    }

    public function getPropertyDetails(Request $request, $id)
    {
        $property_details = Property::find($id);

        $images = PropertyMedia::where('property_id', $id)->where('media_category_id', 1)->get();
        $property_default_rate = PropertyDefaultRate::where('property_id', $id)->get();


        $temp_images = [];
        $temp_rate = [];

        foreach ($images as $key => $val) {
            $temp_data = [
                'category_id' => $val->id,
                'category' => Str::slug($val->MediaSubCategory->name),
                'url' => $val->media_url
            ];
            array_push($temp_images, $temp_data);
        }

        foreach ($property_default_rate as $key => $val) {
            $temp_data = [
                'name' =>$val->hotel_charagable_type->name,
                'category_id' => $val->hotel_charagable_type_id,
                'price' => $val->amount,
                'occupancy_threshold' => $val->chargable_percentage
            ];
            array_push($temp_rate, $temp_data);
        }

        return response()->json([
            'name' => $property_details->name,
            'description' => $property_details->description,
            'address' => $property_details->address,
            'location_id' => $property_details->location_id,
            'cover_image' => $property_details->featured_image,
            'google_embedded_url' => $property_details->gmap_embedded_code,
            'images' => $temp_images,
            'property_charges' => $temp_rate

        ]);

    }
}
