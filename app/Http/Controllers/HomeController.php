<?php

namespace App\Http\Controllers;

use App\Models\BookingSummary;
use App\Models\Leads;
use App\Models\PreBookingSummary;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
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
        $properties_count = $q->count();

        $pre_bookings = PreBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $pre_bookings->whereIn('property_id',$property_id);
        }
        $pre_bookings_count = $pre_bookings->count();

        $bookings = BookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $bookings->whereIn('property_id',$property_id);
        }

        $leads_count = Leads::select(DB::raw('count(*) as count, status'))
            ->groupBy('status')
            ->orderBy('count', 'DESC')
            ->get()
            ->toArray();
        $bookings_count = $bookings->count();

        return view('home', compact('properties_count','pre_bookings_count','bookings_count', 'leads_count'));
    }
}
