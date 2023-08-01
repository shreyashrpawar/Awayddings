<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\ArtistPerson;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        return view('app.artists.index',compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.artists.create');
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
            'artist_image' => 'required',
            'artist_name' => 'required',
        ]);

        // Check if the artist name already exists
        $artistExists = Artist::where('name', $request->artist_name)->exists();
        if ($artistExists) {
            $request->session()->flash('error', 'The selected artist already exists.');
            return redirect()->back();
        }

        // Check if the file input exists in the request.
        if ($request->hasFile('artist_image')) {
            // Retrieve the uploaded file from the request.
            $image = $request->file('artist_image');

            // Check if the file is valid.
            if ($image->isValid()) {
                // Save the file to the desired location.
                $name = time() . $image->getClientOriginalName();
                $filePath = 'images/'. $name;
                Storage::disk('s3')->put($filePath, file_get_contents($image),'public');
    
                // Create the artist record.
                $artist = Artist::create([
                    'name' => $request->input('artist_name'),
                    'status' => 1,
                    // Add other artist fields as needed.
                ]);
                // Associate the image with the artist.
                $artist->image()->create(['url' =>  Storage::disk('s3')->url($filePath)]);
                // Handle other form fields and redirect as needed.
                $request->session()->flash('success', 'Successfully Saved');
                return redirect(route('artists.index'));
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
                // File is not valid. Handle the error appropriately.
            }
        } else {
            $request->session()->flash('error', 'Artist image file input was not found in the request.');
            return redirect()->back();
            // 'artist_image' file input was not found in the request.
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $artist = Artist::with('image')->find($id);
        // $artistImage = $artist->image;
        // dd($artist->image);
        return view('app.artists.edit',compact('artist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        $request->validate([
            'artist_image' => 'required',
            'artist_name' => 'required',
        ]);
    
        $artist = Artist::findOrFail($request->artist_id);
    
        // Update other artist information based on the form fields.
    
        // Check if a new image is being uploaded
        if ($request->hasFile('artist_image')) {
            $image = $request->file('artist_image');
    
            // Check if the file is valid
            if ($image->isValid()) {
                // Delete the existing image if one exists
                if ($artist->image) {
                    Storage::disk('public')->delete($artist->image->url);
                    $artist->image->delete();
                }
    
                // Save the new image to the desired location
                $url = $image->store('images', 'public');
    
                // Create and save the new image record
                // $newImage = new Image(['url' => $url]);
                // $newImage->save();
    
                // Associate the new image with the artist
                $artist->image()->create(['url' => $url]);
            } else {
                $request->session()->flash('error', 'File is not valid.');
                return redirect()->back();
            }
        }
    
        // Save other changes to the artist and redirect
        $artist->update([
            'name' => $request->input('artist_name'),
            // 'status' => $request->input('artist_status'),
            // Add other artist fields as needed.
        ]);
    
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->route('artists.edit', $request->artist_id);
    }

    public function artist_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = Artist::find($id);
        if (!$record) {
            return response()->json(['error' => 'Artist not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        //
    }

    /******* Artist Person ******/

    public function artist_person_view()
    {
        $artist_persons = ArtistPerson::orderBy('id', 'DESC')->get();
        return view('app.artist_person.index',compact('artist_persons'));
    }

    public function artist_person_create()
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        return view('app.artist_person.create',compact('artists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function artist_person_store(Request $request)
    {
        try {

            $request->validate([
                'artist_person_name' => 'required',
                'artist_person_price' => 'required',
                'artist_id' => 'required',
            ]);

            // Check if the artist name already exists
            $artistExists = ArtistPerson::where('name', $request->artist_person_name)->exists();
            if ($artistExists) {
                $request->session()->flash('error', 'The selected artist already exists.');
                return redirect()->back();
            }
        
            // Create the artist record.
            $artist = ArtistPerson::create([
                'name' => $request->input('artist_person_name'),
                'price' => $request->input('artist_person_price'),
                'artist_id' => $request->input('artist_id'),
                'status' => 1,
                // Add other artist fields as needed.
            ]);

            $request->session()->flash('success', 'Successfully Saved');
            return redirect(route('artist_person'));
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the database operation
            $request->session()->flash('error', 'An error occurred while saving the Artist Person.');
            return redirect()->back();
        }
    }

    public function artist_person_edit($id)
    {
        $artists = Artist::orderBy('id', 'DESC')->get();
        $artist_person = ArtistPerson::find($id);
        return view('app.artist_person.edit',compact('artists','artist_person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function artist_person_update(Request $request, Artist $artist)
    {
        $request->validate([
            'artist_person_name' => 'required',
            'artist_person_price' => 'required',
            'artist_id' => 'required',
        ]);
    
        $artist_person = ArtistPerson::findOrFail($request->artist_person_id);
    
        // Save other changes to the artist and redirect
        $artist_person->update([
            'name' => $request->input('artist_person_name'),
            'price' => $request->input('artist_person_price'),
            'artist_id' => $request->input('artist_id'),
            // 'status' => $request->input('artist_person_status'),
            // Add other artist fields as needed.
        ]);
    
        $request->session()->flash('success', 'Successfully Updated');
        return redirect()->route('artist_person_edit', $request->artist_id);
    }

    public function artistPerson_updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        // Assuming you have a model named YourModel with a 'status' column
        $record = ArtistPerson::find($id);
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $record->status = $status;
        $record->save();

        return response()->json(['success' => 'Status updated successfully']);
    }
}
