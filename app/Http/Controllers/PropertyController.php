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
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public function index(Request  $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        $q = Property::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('id',$property_id);
        }
        $properties = $q->paginate(50);
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
        try {
            $property_basic_details = [
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address,
                'alias_name' => $request->name,
                'location_id' => $request->location_id,
                'gmap_embedded_code' => $request->google_embedded_url,
                'featured_image' => $request->cover_image,
                'status' => 0
            ];
            DB::beginTransaction();
            $user = $request->user();
            $roles = $user->getRoleNames();

            $propertyDetails = Property::create($property_basic_details);

            if(count($request->property_charges) > 0 ) {
                foreach ($request->property_charges as $charges) {
                    if ($charges['category_id'] == 1) {
                        $qty = $request->total_rooms;
                        $occupancy_threshold = 100;
                    } elseif ($charges['category_id'] == 2) {
                        $qty = $request->triple_occupancy_rooms;
                        $occupancy_threshold = 100;
                    } else {
                        $qty = ($charges['price'] > 0) ? 1 : 0;
                        $occupancy_threshold = $charges['occupancy_threshold'];
                    }
                    $propertyRate = [
                        'property_id' => $propertyDetails->id,
                        'hotel_charagable_type_id' => $charges['category_id'],
                        'amount' => $charges['price'],
                        'qty' => $qty,
                        'chargable_percentage' => $occupancy_threshold
                    ];
                    PropertyDefaultRate::create($propertyRate);
                }
            }


            if(count($request->amenities) > 0 ) {
                foreach ($request->amenities as $id) {
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
            if(count($request->room_inclusions) > 0 ) {
                foreach ($request->room_inclusions as $id) {
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

            if(count($request->images) > 0 ) {
                foreach ($request->images as $key => $val) {
                    if (array_key_exists('category_id', $val)) {
                        $temp_data = MediaSubCategory::where('id', $val['category_id'])->first();
                        try {
                            PropertyMedia::create([
                                'property_id' => $propertyDetails->id,
                                'media_category_id' => $temp_data->media_category_id,
                                'media_sub_category_id' => $val['category_id'],
                                'media_url' => $val['url']
                            ]);
                        } catch (Throwable $e) {
                            return $e;
                            return false;
                        }
                    }
                }
            }
            if(count($request->videos) > 0 ) {
                foreach ($request->videos as $key => $val) {
                    if (array_key_exists('category_id', $val) && $val['url']) {
                        $temp_data = MediaSubCategory::where('id', $val['category_id'])->first();
                        try {
                            PropertyMedia::create([
                                'property_id' => $propertyDetails->id,
                                'media_category_id' => $temp_data->media_category_id,
                                'media_sub_category_id' => $val['category_id'],
                                'media_url' => $val['url']
                            ]);
                        } catch (Throwable $e) {
                            return $e;
                            return false;
                        }
                    }
                }
            }

            if (in_array("vendor", $roles->toArray())){

                $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
                $vendor_id =  $userVendor->vendor_id;
                VendorPropertyAlignment::create([
                   'vendor_id' => $vendor_id,
                   'property_id' => $propertyDetails->id
                ]);
            }


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $propertyDetails
            ]);
        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            //throw $e; //sometime you want to rollback AND throw the exception
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
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

        $data = Property::find($id);
        return view('app.property.show', compact('data'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function edit(REquest $request,Property $property)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();

        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            VendorPropertyAlignment::where('property_id',$property->id)
                ->where('vendor_id',$vendor_id)
                ->firstOrfail();
        }
        
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
    public function update(Request $request, $id)
    {

        $property = Property::find($id);
        try {
            $property_basic_details = [
                'name' => $request->name,
                'description' => $request->description,
                'address' => $request->address,
                'alias_name' => $request->name,
                'location_id' => $request->location_id,
                'gmap_embedded_code' => $request->google_embedded_url,
                'featured_image' => $request->cover_image,
                'status' => 0
            ];
            DB::beginTransaction();
            $propertyDetails = $property->update($property_basic_details);

            $delete = PropertyDefaultRate::where('property_id',$property->id)->delete();
            if(count($request->property_charges) > 0 ) {
                foreach ($request->property_charges as $charges) {
                    Log::info($charges);
                    if ($charges['category_id'] == 1) {
                        $qty = $request->total_rooms;
                        $occupancy_threshold = 100;
                    } elseif ($charges['category_id'] == 2) {
                        $qty = $request->triple_occupancy_rooms;
                        $occupancy_threshold = 100;
                    } else {
                        $qty = ($charges['price'] > 0) ? 1 : 0;
                        $occupancy_threshold = $charges['occupancy_threshold'];
                    }
                    $propertyRate = [
                        'property_id' => $property->id,
                        'hotel_charagable_type_id' => $charges['category_id'],
                        'amount' => $charges['price'],
                        'qty' => $qty,
                        'chargable_percentage' => $occupancy_threshold
                    ];

                    PropertyDefaultRate::create($propertyRate);
                }
            }
            $delete = PropertyAmenities::where('property_id',$property->id)->delete();
            if(count($request->amenities) > 0 ) {
                foreach ($request->amenities as $id) {
                    $amenities_data = [
                        'property_id' =>  $property->id,
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
            $delete = PropertyRoomInclusion::where('property_id',$property->id)->delete();
            if(count($request->room_inclusions) > 0 ) {
                foreach ($request->room_inclusions as $id) {
                    $room_inclusion_details = [
                        'property_id' =>  $property->id,
                        'hotel_facility_id' => $id
                    ];
                    try {
                        PropertyRoomInclusion::create($room_inclusion_details);
                    } catch (Throwable $e) {
                        return false;
                    }
                }
            }


             PropertyMedia::where('property_id',$property->id)
                ->whereIn('media_category_id',[1,3])->delete();
            if(count($request->images) > 0 ) {
                foreach ($request->images as $key => $val) {
                    if (array_key_exists('category_id', $val)) {
                        $temp_data = MediaSubCategory::where('id', $val['category_id'])->firstOrFail();

                        try {
                            PropertyMedia::create([
                                'property_id' => $property->id,
                                'media_category_id' => $temp_data->media_category_id,
                                'media_sub_category_id' => $val['category_id'],
                                'media_url' => $val['url']
                            ]);
                        } catch (Throwable $e) {
                            return $e;
                            return false;
                        }
                    }
                }
            }
            PropertyMedia::where('property_id',$property->id)
                            ->whereIn('media_category_id',[2])->delete();
            if(count($request->videos) > 0 ) {
                foreach ($request->videos as $key => $val) {
                    if (array_key_exists('category_id', $val) && $val['url']) {
                        $temp_data = MediaSubCategory::where('id', $val['category_id'])->first();
                        try {
                            PropertyMedia::create([
                                'property_id' => $property->id,
                                'media_category_id' => $temp_data->media_category_id,
                                'media_sub_category_id' => $val['category_id'],
                                'media_url' => $val['url']
                            ]);
                        } catch (Throwable $e) {
                            return $e;
                            return false;
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $property
            ]);
        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            //throw $e; //sometime you want to rollback AND throw the exception
        }
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

        $images = PropertyMedia::where('property_id', $id)->get();
        $videos = PropertyMedia::where('property_id', $id)->where('media_category_id',2)->get();
        $property_default_rate = PropertyDefaultRate::where('property_id', $id)->get();
        $property_amenities = PropertyAmenities::where('property_id', $id)->get();
        $property_room_inclusions = PropertyRoomInclusion::where('property_id', $id)->get();

        $temp_images = [];
        $temp_videos = [];
        $temp_rate = [];
        $temp_amenities = [];
        $temp_room_inclusions = [];

        foreach ($images as $key => $val) {
            $temp_data = [
                'category_id' => $val->media_sub_category_id,
                'category' => Str::slug($val->MediaSubCategory->name),
                'url' => $val->media_url
            ];
            array_push($temp_images, $temp_data);
        }
        foreach($videos as $key => $val){
            $temp_data = [
                'category_id' => $val->media_sub_category_id,
                'category' => Str::slug($val->MediaSubCategory->name),
                'name' => $val->MediaSubCategory->name,
                'url' => $val->media_url
            ];
            array_push($temp_videos, $temp_data);
        }
        foreach ($property_default_rate as $key => $val) {
            if ($val->hotel_charagable_type_id == 1) {
                $double_occupancy_rate = $val->qty;
            } elseif ($val->hotel_charagable_type_id == 2) {
                $triple_occupancy_rate = $val->qty;
            }
            $temp_data = [
                'name' => $val->hotel_charagable_type->name,
                'category_id' => $val->hotel_charagable_type_id,
                'price' => $val->amount,
                'occupancy_threshold' => $val->chargable_percentage
            ];
            array_push($temp_rate, $temp_data);
        }
        foreach ($property_amenities as $key => $val) {
            array_push($temp_amenities, $val->hotel_facility_id);
        }

        foreach ($property_room_inclusions as $key => $val) {
            array_push($temp_room_inclusions, $val->hotel_facility_id);
        }

        return response()->json([
            'name' => $property_details->name,
            'description' => $property_details->description,
            'address' => $property_details->address,
            'location_id' => $property_details->location_id,
            'cover_image' => $property_details->featured_image,
            'google_embedded_url' => $property_details->gmap_embedded_code,
            'images' => $temp_images,
            'property_charges' => $temp_rate,
            'amenities' => $temp_amenities,
            'room_inclusions' => $temp_room_inclusions,
            'triple_occupancy_rooms' => $triple_occupancy_rate,
            'total_rooms' => $double_occupancy_rate,
            'videos' => $temp_videos
        ]);

    }
    public function updatePropertyStatus(Request $request){
        $property_id = $request->property_id;
        $status = $request->status;
        $propertyDetails = Property::where('id',$property_id)
            ->update([
                'status' => $status
            ]);
        return response()->json([
           'success' => true,
           'message' => 'SUCCESS'
        ]);
    }
}
