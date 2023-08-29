<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmAddonFacility;
use App\Models\EmAddonFacilityDetails;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;


class AddonFacilityDetailsController extends Controller
{
    public function index(Request $request)
    {
        $facilityId = $request->input('facility_id');
        $facilityDetails = EmAddonFacilityDetails::with('image')->where('em_addon_facility_id', $facilityId)->get();
        $facilities = EmAddonFacility::all(); // Fetch all addon facilities for the dropdown
        return view('app.addon_facilty.facility_details_listing', compact('facilityDetails', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'facility_details_image' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);
        $facilityDetails = EmAddonFacilityDetails::findOrFail($id);

        if ($request->hasFile('facility_details_image')) {
            $image = $request->file('facility_details_image');
    
            // Check if the file is valid
            if ($image->isValid()) {
                // Delete the existing image if one exists
                if ($facilityDetails->image) {
                    Storage::disk('public')->delete($facilityDetails->image->url);
                    $facilityDetails->image->delete();
                } else{
                    $name = time() . $image->getClientOriginalName();
                    $filePath = 'images/'. $name;
                    Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
                    // $facilityDetails->image()->create(['url' =>  Storage::disk('s3')->url($filePath)]);
                }
    
                // $url = $image->store('images', 'public');
                
                // $facilityDetails->image()->create(['url' => $url]);
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
                $facilityDetails->image()->create(['url' => Storage::disk('s3')->url($filePath)]);
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
            }
        }
        $facilityDetails->price = $request->input('price');
        $facilityDetails->description = $request->input('description');
        $facilityDetails->save();

        return redirect()->route('addon_facilities.index')->with('success', 'Addon Facility Details updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_details_image' => 'required',
            'facility_id' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('facility_details_image')) {
            // Retrieve the uploaded file from the request.
            $image = $request->file('facility_details_image');

            // Check if the file is valid.
            if ($image->isValid()) {
                // Save the file to the desired location.
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
    
                $facilityDetails = new EmAddonFacilityDetails();
                $facilityDetails->em_addon_facility_id = $request->input('facility_id');
                $facilityDetails->price = $request->input('price');
                $facilityDetails->description = $request->input('description');
                $facilityDetails->save();
                
                $facilityDetails->image()->create(['url' =>  Storage::disk('s3')->url($filePath)]);
                // Handle other form fields and redirect as needed.
                $request->session()->flash('success', 'Successfully Saved');
                return redirect()->back();
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
                // File is not valid. Handle the error appropriately.
            }
        } else {
            $request->session()->flash('error', 'Image file input was not found in the request.');
            return redirect()->back();
        }

        

        // return redirect()->back();
    }

    public function facilityDetails_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = EmAddonFacilityDetails::find($id);
        if (!$record) {
            return response()->json(['error' => 'Facility details not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
    }
}
