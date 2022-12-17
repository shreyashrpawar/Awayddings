<?php

namespace App\Http\Controllers;

use App\Models\HotelChargableType;
use App\Models\HotelFacility;
use App\Models\Leads;
use App\Models\Location;
use App\Models\MediaSubCategory;
use App\Models\Property;
use App\Models\PropertyAmenities;
use App\Models\PropertyDefaultRate;
use App\Models\PropertyMedia;
use App\Models\PropertyRoomInclusion;
use App\Models\RoomInclusion;
use App\Models\User;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leads = Leads::whereNull('deleted_at')->orderBy('id', 'desc')->get();
        $leads_statuses = Leads::distinct('status')->get(['status'])->toArray();
        return view('app.leads.index', compact('leads', 'leads_statuses'));
    }

    public function update(Request $request, $lead_id)
    {
        $lead = Leads::findOrFail($lead_id);
        $lead->status = $request->lead_status;
        $lead->remarks = $request->lead_remarks;
        $lead->save();
        return back()->with('success', 'Lead updated successfully!');
    }

    public function store(Request $request)
    {
        $db_data = array(
            'name' => $request->customer_name,
            'email' => $request->customer_email,
            'mobile' => $request->customer_mobile,
            'bride_groom' => $request->bride_groom,
            'wedding_date' => $request->customer_date,
            'pax' => $request->customer_pax,
            'status' => 'new',
            'origin' => 'google_ads'
        );

        Leads::create($db_data);
        return back()->with('success', 'Lead created successfully!');
    }

    public function destroy($id)
    {
        Leads::find($id)->delete();
        return back()->with('success','Leads deleted successfully!');
    }
}
