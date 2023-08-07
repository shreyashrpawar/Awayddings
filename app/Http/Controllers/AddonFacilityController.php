<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmAddonFacility;
use App\Models\EmAddonFacilityDetails;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class AddonFacilityController extends Controller
{
    public function index()
    {
        $facilities = EmAddonFacility::all();
        return view('app.addon_facilty.list_addon_facilities', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required',
        ]);

        $facility = new EmAddonFacility();
        $facility->name = $request->input('facility_name');
        $facility->save();

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'facility_name' => 'required',
        ]);

        $facility = EmAddonFacility::findOrFail($id);
        $facility->name = $request->input('facility_name');
        $facility->save();

        return redirect()->back();
    }

    public function facility_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = EmAddonFacility::find($id);
        if (!$record) {
            return response()->json(['error' => 'Facility not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
    }
}
