<?php

namespace App\Http\Controllers;

use App\Jobs\SendInstallmentEmail;
use App\Jobs\SendCongratsEmail;
use App\Jobs\SendEmailToHotel;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Models\EventBookingSummary;
use Illuminate\Http\Request;
use PDF;
use Storage;
use App\Jobs\GeneratePDF;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use App\Models\User;
use App\Models\Property;
use App\Models\Vendor;

class EventBookingSummaryController extends Controller
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        
        $q = EventBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        $bookings = $q->get();
        return view('app.event_bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $bookings = EventBookingSummary::find($id);
        // dd($bookings);

        return view('app.event_bookings.show', compact('bookings'));
    }
}
