<?php

namespace App\Http\Controllers;

use App\Models\Decoration;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class DecorationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $decors = Decoration::orderBy('id', 'DESC')->get();
        return view('app.decorations.index',compact('decors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.decorations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'decoration_image' => 'required',
            'decoration_name' => 'required',
            'decoration_price' => 'required',
            'decoration_description' => 'required',
        ]);

        // Check if the file input exists in the request.
        if ($request->hasFile('decoration_image')) {
            // Retrieve the uploaded file from the request.
            $image = $request->file('decoration_image');

            // Check if the file is valid.
            if ($image->isValid()) {
                // Save the file to the desired location.
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
    
                // Create the decoration record.
                $decoration = Decoration::create([
                    'name' => $request->input('decoration_name'),
                    'price' => $request->input('decoration_price'),
                    'description' => $request->input('decoration_description'),
                    'status' => 1,
                    // Add other decoration fields as needed.
                ]);
    
                // Associate the image with the decoration.
                $decoration->image()->create(['url' => Storage::disk('s3')->url($filePath)]);
    
                // Handle other form fields and redirect as needed.
                $request->session()->flash('success', 'Successfully Saved');
                return redirect(route('decorations.index'));
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
                // File is not valid. Handle the error appropriately.
            }
        } else {
            $request->session()->flash('error', 'Decoration image file input was not found in the request.');
            return redirect()->back();
            // 'decoration_image' file input was not found in the request.
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Decoration  $decoration
     * @return \Illuminate\Http\Response
     */
    public function show(Decoration $decoration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Decoration  $decoration
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $decoration = Decoration::with('image')->find($id);
        return view('app.decorations.edit',compact('decoration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Decoration  $decoration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Decoration $decoration)
    {
        $request->validate([
            // 'decoration_image' => 'required',
            'decoration_name' => 'required',
            'decoration_price' => 'required',
            'decoration_description' => 'required',
        ]);
    
        $decoration = Decoration::findOrFail($request->decoration_id);
    
        // Update other decoration information based on the form fields.
    
        // Check if a new image is being uploaded
        if ($request->hasFile('decoration_image')) {
            $image = $request->file('decoration_image');
    
            // Check if the file is valid
            if ($image->isValid()) {
                // Delete the existing image if one exists
                if ($decoration->image) {
                    Storage::disk('public')->delete($decoration->image->url);
                    $decoration->image->delete();
                }
    
                // Save the new image to the desired location
                $url = $image->store('images', 'public');
    
                // Create and save the new image record
                // $newImage = new Image(['url' => $url]);
                // $newImage->save();
    
                // Associate the new image with the decoration
                $decoration->image()->create(['url' => $url]);
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
            }
        }
    
        // Save other changes to the decoration and redirect
        $decoration->update([
            'name' => $request->input('decoration_name'),
            'price' => $request->input('decoration_price'),
            'description' => $request->input('decoration_description'),
            // 'status' => $request->input('decoration_status'),
            // Add other decoration fields as needed.
        ]);
    
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->route('decorations.edit', $request->decoration_id);
    }

    public function decoration_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = Decoration::find($id);
        if (!$record) {
            return response()->json(['error' => 'Decoration not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Decoration  $decoration
     * @return \Illuminate\Http\Response
     */
    public function destroy(Decoration $decoration)
    {
        //
    }
}
