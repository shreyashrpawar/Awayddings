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
        $request->validate([
            'light_sound_image' => 'required',
            'light_sound_price' => 'required',
            'light_sound_description' => 'required',
        ]);
        
        if ($request->hasFile('light_sound_image')) {
            $image = $request->file('light_sound_image');

            if ($image->isValid()) {
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
                
                $light = LightandSound::create([
                    'price' => $request->light_sound_price,
                    'description' => $request->light_sound_description,
                    'status' => 1,
                ]);
    
                $light->image()->create(['url' =>  Storage::disk('s3')->url($filePath)]);
    
                $request->session()->flash('success', 'Successfully Saved');
                return redirect(route('lightandsounds.index'));
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', 'Light and Sound image file input was not found in the request.');
            return redirect()->back();
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
        $request->validate([
            'light_sound_image' => 'required',
            'light_sound_price' => 'required',
            'light_sound_description' => 'required',
        ]);
        
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
            'price' => $request->light_sound_price,
            'description' => $request->light_sound_description,
            // 'status' => $request->input('light_sound_status'),
            // Add other light_sound fields as needed.
        ]);
    
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->route('lightandsounds.edit', $request->light_sound_id);
    }

    public function lightandsound_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = LightandSound::find($id);
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
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
