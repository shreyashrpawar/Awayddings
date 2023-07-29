<?php

namespace App\Http\Controllers;

use App\Models\LightandSound;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class LightandSoundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lightandSounds = LightandSound::with('image')->orderBy('id', 'DESC')->get();
        return view('app.lightandsounds.index',compact('lightandSounds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.lightandsounds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if the file input exists in the request.
        if ($request->hasFile('light_sound_image')) {
            // Retrieve the uploaded file from the request.
            $image = $request->file('light_sound_image');

            // Check if the file is valid.
            if ($image->isValid()) {
                // Save the file to the desired location.
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
    
                // Create the light record.
                $light = LightandSound::create([
                    'status' => $request->input('light_sound_status'),
                    // Add other light fields as needed.
                ]);
    
                // Associate the image with the light.
                $light->image()->create(['url' =>  Storage::disk('s3')->url($filePath)]);
    
                // Handle other form fields and redirect as needed.
                $request->session()->flash('success', 'Successfully Saved');
                return redirect(route('lightandsounds.index'));
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
                // File is not valid. Handle the error appropriately.
            }
        } else {
            $request->session()->flash('error', 'Light and Sound image file input was not found in the request.');
            return redirect()->back();
            // 'light_image' file input was not found in the request.
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LightandSound  $lightandSound
     * @return \Illuminate\Http\Response
     */
    public function show(LightandSound $lightandSound)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LightandSound  $lightandSound
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $light_sound = LightandSound::with('image')->find($id);
        return view('app.lightandsounds.edit',compact('light_sound'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LightandSound  $lightandSound
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LightandSound $lightandSound)
    {
        $light_sound = LightandSound::findOrFail($request->light_sound_id);

        if ($request->hasFile('light_sound_image')) {
            $image = $request->file('light_sound_image');
    
            if ($image->isValid()) {
                if ($light_sound->image) {
                    Storage::disk('public')->delete($light_sound->image->url);
                    $light_sound->image->delete();
                }
    
                $url = $image->store('images', 'public');
                $light_sound->image()->create(['url' => $url]);
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
            }
        }
    
        // Save other changes to the light_sound and redirect
        $light_sound->update([
            'status' => $request->input('light_sound_status'),
            // Add other light_sound fields as needed.
        ]);
    
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->route('lightandsounds.edit', $request->light_sound_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LightandSound  $lightandSound
     * @return \Illuminate\Http\Response
     */
    public function destroy(LightandSound $lightandSound)
    {
        //
    }
}
