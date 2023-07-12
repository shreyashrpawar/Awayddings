<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingSummary;
use App\Models\HotelChargableType;
use App\Models\Property;
use App\Models\PropertyAmenities;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyMedia;
use App\Models\PropertyRate;
use App\Models\PropertyRoomInclusion;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function getRandomProperty(Request $request)
    {
        $adults = 50;
        $now = Carbon::now();
        $double_room_count = $adults / 2;
        $double_occupancy_details = HotelChargableType::where('name', 'Double Occupancy Room')->where('status', 1)->first();
        $start_date = Carbon::parse(Carbon::now());
        $end_date = Carbon::now()->addDays(2);

        $nights = $start_date->diffInDays($end_date);

        $properties = Property::join('locations', 'locations.id', '=', 'properties.location_id')
            ->join('property_default_rates', 'property_default_rates.property_id', '=', 'properties.id')
            ->where('properties.status', 1)
            ->where('property_default_rates.hotel_charagable_type_id', $double_occupancy_details->id)
            ->where('property_default_rates.qty', '>=', $double_room_count)
            ->select('properties.id', 'properties.name', 'properties.featured_image', 'properties.description', 'locations.name as location',
                'property_default_rates.amount', 'property_default_rates.qty')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // get double occupancy price for today
        // room availability
        foreach ($properties as $key => $property) {
            $custom_rate_double_occupancy = PropertyRate::where('property_id', $property->id)
                ->whereDate('date', $now)
                ->where('hotel_chargable_type_id', $double_occupancy_details->id)->first();
            if (!$custom_rate_double_occupancy) {
                $property->amount = $property->amount * $double_room_count * $nights;
                $property->flag = true;
                $property->night = $nights;
                $property->pax = $adults;
            } else {
                $property->amount = $custom_rate_double_occupancy->amount * $double_room_count * $nights;
                $property->night = $nights;
                $property->flag = false;
                $property->pax = $adults;
            }


        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
            'data' => $properties
        ]);
    }

    public function searchProperty(Request $request)
    {
        $adults = $request->adults;
        $location_id = $request->location_id;
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $nights = $start_date->diffInDays($end_date);
        $days = $nights + 1;

        $nights = $start_date->diffInDays($end_date);
        $budget = $request->budget;

        $double_room_count = $adults / 2;
        $double_occupancy_details = HotelChargableType::where('name', 'Double Occupancy Room')->where('status', 1)->first();

        $properties = Property::join('locations', 'locations.id', '=', 'properties.location_id')
            ->join('property_default_rates', 'property_default_rates.property_id', '=', 'properties.id')
            ->where('properties.status', 1)
            ->where('properties.location_id', $location_id)
            ->where('property_default_rates.hotel_charagable_type_id', $double_occupancy_details->id)
            ->where('property_default_rates.qty', '>=', $double_room_count)
            ->select('properties.id', 'properties.name', 'properties.address', 'properties.featured_image', 'properties.description', 'locations.name as location',
                'property_default_rates.amount', 'property_default_rates.qty')
            ->orderBy('property_default_rates.amount', 'ASC')
            ->simplePaginate(15);
        // get double occupancy price for today
        // room availability
        foreach ($properties as $key => $property) {
            $custom_rate_double_occupancy = PropertyRate::where('property_id', $property->id)
                ->where('hotel_chargable_type_id', $double_occupancy_details->id)
                ->first();
            if (!$custom_rate_double_occupancy) {
                $property->amount = $property->amount * $double_room_count * $nights;
                $property->nights = $nights;
                $property->days = $days;
                $property->pax = $adults;
            } else {
                $property->amount = $custom_rate_double_occupancy->amount * $double_room_count * $nights;
                $property->nights = $nights;
                $property->days = $days;
                $property->pax = $adults;
            }
        }


        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
            'data' => $properties
        ]);
    }

    public function propertyDetails(Request $request, $id)
    {
        $properties = Property::join('locations', 'locations.id', '=', 'properties.location_id')
            ->join('property_default_rates', 'property_default_rates.property_id', '=', 'properties.id')
            ->where('properties.status', 1)
            ->where('properties.id', $id)
            ->select('properties.id',
                'properties.name',
                'properties.wedding_planning_decoration_budget',
                'properties.featured_image as cover_image',
                'locations.name as location',
                'locations.id as location_id',
                'properties.gmap_embedded_code',
                'properties.description',
                'properties.property_terms',
            )
            ->inRandomOrder()
            ->first();


        $properties_images = PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id', 1)
            ->whereIn('media_sub_category_id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
            ->where('property_id', $id)
            ->get();
        //
        $wedding_images = PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id', 1)
            ->whereIn('media_sub_category_id', [11, 18])
            ->where('property_id', $id)
            ->get();

        $wedding_video = PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id', 2)
            ->whereIn('media_sub_category_id', [12])
            ->where('property_id', $id)
            ->get();

        $properties_amenities = PropertyAmenities::where('property_id', $id)
            ->get();

        $properties_room_inclusion = PropertyRoomInclusion::where('property_id', $id)
            ->get();


        $property_menus_data = PropertyMedia::with('MediaSubCategory')
            ->where('media_category_id', 3)
            ->whereIn('media_sub_category_id', [14, 15, 16, 17, 18])
            ->where('property_id', $id)
            ->get();


        $property_images = [];
        $property_wedding_images = [];
        $property_wedding_video = '';
        $property_amenities = [];
        $property_room_inclusions = [];
        $property_menus = [];

        foreach ($property_menus_data as $key => $val) {
            $temp_data = [
                'category' => $val->MediaSubCategory->name,
                'url' => $val->media_url,
            ];
            array_push($property_menus, $temp_data);
        }


        foreach ($properties_images as $key => $val) {
            $temp_data = [
                'category' => $val->MediaSubCategory->name,
                'url' => $val->media_url,
            ];
            array_push($property_images, $temp_data);
        }
        foreach ($wedding_images as $key => $val) {
            $temp_data = [
                'category' => $val->MediaSubCategory->name,
                'url' => $val->media_url,
            ];
            array_push($property_wedding_images, $temp_data);
        }
        foreach ($wedding_video as $key => $val) {
            $property_wedding_video = $val->media_url;
        }

        foreach ($properties_amenities as $key => $val) {
            $temp_data = [
                'id' => $val->id,
                'name' => $val->hotel_facility->name,
            ];
            array_push($property_amenities, $temp_data);
        }
        foreach ($properties_room_inclusion as $key => $val) {
            $temp_data = [
                'id' => $val->id,
                'name' => $val->room_inclusion->name,
            ];
            array_push($property_room_inclusions, $temp_data);
        }


        $properties->images = $property_images;
        $properties->wedding_images = $property_wedding_images;
        $properties->wedding_video = $property_wedding_video;
        $properties->amenities = $property_amenities;
        $properties->room_inclusion = $property_room_inclusions;
        $properties->property_menus = $property_menus;

        //
        $properties->double_occupancy_rate = PropertyDefaultRate::where('property_id', $id)
            ->where('hotel_charagable_type_id', 1)
            ->first()->amount;
        $properties->triple_occupancy_rate = PropertyDefaultRate::where('property_id', $id)
            ->where('hotel_charagable_type_id', 2)
            ->first()->amount;


        $other_properties = Property::select('id', 'name', 'featured_image')->where('status', '=', '1')->whereNotIn('id', [$id])
            ->inRandomOrder()
            ->limit(3)
            ->where('location_id', $properties->location_id)->get();


        $properties->other_properties = $other_properties;

        return response()->json([
            'success' => true,
            'message' => 'SUCCESS',
            'data' => $properties
        ]);

    }

    public function getPropertyBudget(Request $request, $id)
    {
        $property_id = $id;
        $adult = $request->adult;
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        $nights = $start_date->diffInDays($end_date);
        $max_rooms = ceil($adult / 2);
        $min_rooms = ceil($adult / 3);

        // get the property room available

        $properties = Property::join('locations', 'locations.id', '=', 'properties.location_id')
            ->join('property_default_rates', 'property_default_rates.property_id', '=', 'properties.id')
            ->where('properties.id', $property_id)
            ->where('properties.status', 1)
            ->select('properties.id', 'properties.name', 'properties.featured_image', 'properties.description', 'locations.name as location', 'total_rooms')
            ->first();
        // get date range
        $dateRange = CarbonPeriod::create($start_date, $end_date);
        $property_default_rates = PropertyDefaultRate::where('property_id', $property_id)->get();
        $property_chargable = $property_default_rates->unique('hotel_charagable_type_id')->all();
        $property_rates = [];
        foreach ($dateRange as $date) {
            foreach ($property_chargable as $chargable_entity) {
                $propertyRate = PropertyRate::where('property_id', $property_id)
                    ->where('hotel_chargable_type_id', $chargable_entity->hotel_charagable_type_id)->first();
                if ($propertyRate) {
                    $temp_data = [
                        'date' => $date,
                        'chargable_type_id' => $chargable_entity->hotel_charagable_type_id,
                        'chargable_type_details' => $chargable_entity->hotel_charagable_type,
                        'amount' => $propertyRate->amount,
                        'qty' => $propertyRate->available,
                        'percentage_occupancy' => $propertyRate->occupancy_percentage
                    ];
                } else {
                    $temp_data = [
                        'date' => $date,
                        'chargable_type_id' => $chargable_entity->hotel_charagable_type_id,
                        'chargable_type_details' => $chargable_entity->hotel_charagable_type,
                        'amount' => $chargable_entity->amount,
                        'qty' => $chargable_entity->qty,
                        'percentage_occupancy' => $chargable_entity->chargable_percentage
                    ];
                }
                array_push($property_rates, $temp_data);
            }
        }
        // min room count for double and triple
        $min_dbl_room = collect($property_rates)->where('chargable_type_id', 1)->min('qty');
        $min_triple_room = collect($property_rates)->where('chargable_type_id', 2)->min('qty');


        $double_room_rate_avg = collect($property_rates)->where('chargable_type_id', 1)->avg('amount');
        $triple_room_rate_avg = collect($property_rates)->where('chargable_type_id', 2)->avg('amount');


        if ($min_dbl_room < $min_rooms) {
            return response()->json([
                'success' => false,
                'message' => 'No Room Available'
            ]);
        }

        $best_budget = $min_rooms * $triple_room_rate_avg * $nights;
        $best_budget_in_words = $this->formatNumberToLakhs($best_budget);

        $best_budget_plan = [
            'triple_occupancy_room_count' => $min_rooms,
            'double_occupancy_room_count' => 0,
            'budget' => $best_budget,
            'budget_display' => $best_budget_in_words
        ];


        $comfortable_budget_plan = [];
        $mid_budget_plan = [];

        // calculate the comfortable_budget_plan if the double occupancy rooms are available
        if ($min_dbl_room >= $max_rooms) {
            $comfortable_budget = $max_rooms * $double_room_rate_avg * $nights;
            $comfortable_budget_in_words = $this->formatNumberToLakhs($comfortable_budget);
            $comfortable_budget_plan = [
                'triple_occupancy_room_count' => 0,
                'double_occupancy_room_count' => $max_rooms,
                'budget' => $comfortable_budget,
                'budget_display' => $comfortable_budget_in_words
            ];
        }

//      $mid_budget_total_adult = $adult;
//      $mid_budget_half_adult = $mid_budget_total_adult / 2;
//      $mid_budget_double_room_count =  ceil($mid_budget_half_adult/2);
//      $mid_budget_triple_room_count =  ceil($mid_budget_half_adult/3);
//       // calculate the mid_budget_plan if the double occupancy rooms are available and triple occupancy room available
//      if($min_dbl_room >= $mid_budget_double_room_count && $min_triple_room >= $mid_budget_triple_room_count  ) {
//          $mid_budget  = $nights * ($mid_budget_double_room_count * $double_room_rate_avg + $mid_budget_triple_room_count * $triple_room_rate_avg );
//          $mid_budget_in_words =$this->formatNumberToLakhs($mid_budget);
//        $mid_budget_plan = [
//              'double_occupancy_room_count' => $mid_budget_double_room_count,
//          'triple_occupancy_room_count' => $mid_budget_triple_room_count,
//          'budget' => $mid_budget,
//          'budget_display' => $mid_budget_in_words
//        ];
//      }

        return response()->json([
            'success' => true,
            'message' => 'SUCCESS',
            'data' => [
                'total_rooms' => $properties->total_rooms,
                'best_budget_plan' => $best_budget_plan,
//               'mid_budget_plan' => $mid_budget_plan,
                'comfortable_budget_plan' => $comfortable_budget_plan,
                'double_occupancy_rate' => $double_room_rate_avg,
                'triple_occupancy_rate' => $triple_room_rate_avg,
                'nights' => $nights
            ]
        ]);

    }

    public function propertyCountWithLocation(Request $request)
    {
        $data = DB::select("SELECT
                count(locations.name) as count,
                locations.name,
                locations.status
            FROM
                properties
                INNER JOIN locations
            WHERE
                properties.location_id = locations.id  AND
	            locations.status = 1
                group by locations.name, locations.status");
        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
            'data' => $data
        ]);
    }

    public function propertyAvailable(Request $request)
    {
        $property_id = $request->property_id;
        $check_in = Carbon::parse($request->start_date);
        $check_out = Carbon::parse($request->end_date);
        $adults = $request->adult;
        $rooms_required = $adults / 2;
        $propertyDetails = Property::find($property_id);

        if ($propertyDetails->total_rooms < $rooms_required)
            return response()->json([
                'success' => false,
                'message' => 'Requirement is more than the rooms available',
            ]);

        $hasBooking = BookingSummary::where('property_id', '=', $property_id)
            ->where('check_out', '>=', $check_in)
            ->where('check_in', '<=', $check_out)
            ->count();

        if ($hasBooking)
            return response()->json([
                'success' => false,
                'message' => 'Rooms not available for following dates',
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Rooms are available'
        ]);

    }

    public function getPropertyDetails(Request $request)
    {
        $property_id = $request->property_id;
        $check_in = Carbon::parse($request->check_in);
        $check_out = Carbon::parse($request->check_out);
        $temp_checkout_date = $check_out->subDay();
        $adults = $request->adults;

        $nights = $check_in->diffInDays($check_out);
        $days = $nights + 1;
        $max_rooms = ceil($adults / 2);
        $min_rooms = ceil($adults / 3);

        $dateRange = CarbonPeriod::create($check_in, $temp_checkout_date);

        $propertDetails = Property::find($property_id);
        $property_chargable_items =
            PropertyDefaultRate::with('hotel_charagable_type')
                ->where('property_id', $property_id)
                ->where('amount', '>', 0)
                ->get();


        $property_rates = [];

        foreach ($dateRange as $date) {
            $temp_data = [
                'date' => $date->format('d-m-Y'),
                'data' => []
            ];
            foreach ($property_chargable_items as $key => $val) {
                $propertyRate = PropertyRate::where('property_id', $property_id)
                    ->where('hotel_chargable_type_id', $val->hotel_charagable_type_id)
                    ->first();
                if ($propertyRate) {
                    $temp_data1 = [
                        'total_rooms' => $propertDetails->total_rooms,
                        'date' => $date->format('d-m-Y'),
                        'chargable_type_id' => $val->hotel_charagable_type_id,
                        'chargable_type_details' => $val->hotel_charagable_type->name,
                        'chargable_type_is_starter' => $val->hotel_charagable_type->is_single_qty,
                        'rate' => $propertyRate->amount,
                        'qty' => $propertyRate->available,
                        'percentage_occupancy' => $propertyRate->occupancy_percentage
                    ];
                } else {
                    $temp_data1 = [
                        'total_rooms' => $propertDetails->total_rooms,
                        'date' => $date->format('d-m-Y'),
                        'chargable_type_id' => $val->hotel_charagable_type_id,
                        'chargable_type_details' => $val->hotel_charagable_type->name,
                        'chargable_type_is_starter' => $val->hotel_charagable_type->is_single_qty,
                        'rate' => $val->amount,
                        'qty' => $val->qty,
                        'percentage_occupancy' => $val->chargable_percentage
                    ];
                }

                array_push($temp_data['data'], $temp_data1);
            }
            array_push($property_rates, $temp_data);
        }
        // echo '<pre>'; print_r($property_rates); exit;

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $property_rates
        ]);


    }

    private function formatNumberToLakhs($n, $precision = 2)
    {

        if ($n < 900) {
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 100000, $precision);
            $suffix = 'L';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 10000000, $precision);
            $suffix = 'Cr';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }
}
