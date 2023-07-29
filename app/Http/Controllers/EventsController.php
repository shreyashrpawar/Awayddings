<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Decoration;
use App\Models\Artist;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::orderBy('id', 'DESC')->get();
        return view('app.events.index',compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $decorations = Decoration::all();
        $artists = Artist::all();
        return view('app.events.create', compact('artists', 'decorations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $eventExists = Event::where('name', $request->event_name)->exists();

            if ($eventExists == false) {
                $eventName = $request->input('event_name');
                $eventDescription = $request->input('event_description');
                $isArtistVisible = $request->input('is_artist_visible');
                $isDecorVisible = $request->input('is_decor_visible');
                $artists = $request->input('artists'); // This will be an array of selected artist IDs
                $decorations = $request->input('decorations'); // This will be an array of selected decoration IDs
                $eventStatus = $request->input('event_status');

                // Now you can save these values to your database or perform any other operations as needed.
                // For example:
                
                $event = new Event;
                $event->name = $eventName;
                $event->description = $eventDescription;
                $event->is_artist_visible = $isArtistVisible;
                $event->is_decor_visible = $isDecorVisible;
                $event->status = $eventStatus;
                $event->save();

                // Attach artists and decorations to the event
                $event->artists()->attach($artists);
                $event->decorations()->attach($decorations);
                $request->session()->flash('success','Successfully Saved');
                return redirect(route('events.index'));
            } else {
                $request->session()->flash('error', 'The selected event already exists.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the database operation
            $request->session()->flash('error', 'An error occurred while saving the event.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $event = Event::find($id);
        $event = Event::with('artists', 'decorations')->find($id); // Fetch the event along with its associated artists and decorations
        $artists = Artist::all();
        $decorations = Decoration::all();
        return view('app.events.edit',compact('event', 'artists', 'decorations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        // try {
            $eventExists = Event::where('name', $request->event_name)->where('id', '<>', $request->event_id)->exists();
            $event = Event::find($request->event_id);

            if ($eventExists == false) {
                // $event->update([
                //     'name' => $request->event_name,
                //     'description' => $request->event_description,
                //     'status' => $request->event_status,
                //     'is_artist_visible' => $request->is_artist_visible,
                //     'is_decor_visible' => $request->is_decor_visible,
                // ]);
                $request->validate([
                    'event_name' => 'required|string|max:255',
                    'event_description' => 'nullable|string',
                    'is_artist_visible' => 'required|boolean',
                    'is_decor_visible' => 'required|boolean',
                    'event_status' => 'required|boolean',
                    'artists' => 'nullable|array', // Make sure 'artists' is an array
                    'artists.*' => 'exists:artists,id', // Make sure all artists exist in the 'artists' table
                    'decorations' => 'nullable|array', // Make sure 'decorations' is an array
                    'decorations.*' => 'exists:decorations,id', // Make sure all decorations exist in the 'decorations' table
                ]);
            
                // Find the event by its ID
                // $event = Event::findOrFail($id);
            
                // Update the event details
                $event->update([
                    'name' => $request->event_name,
                    'description' => $request->event_description,
                    'is_artist_visible' => $request->is_artist_visible,
                    'is_decor_visible' => $request->is_decor_visible,
                    'status' => $request->event_status,
                ]);
            
                // Sync the associated artists and decorations with the event
                $event->artists()->sync($request->artists);
                $event->decorations()->sync($request->decorations);
                $request->session()->flash('success','Successfully Updated');
                return redirect(route('events.index'));
            } else {
                $request->session()->flash('error', 'The selected time slot already exists.');
                return redirect()->back();
            }
        // } catch (\Exception $e) {
        //     // Handle any exceptions that might occur during the database operation
        //     $request->session()->flash('error', 'An error occurred while saving the time slot.');
        //     return redirect()->back();
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
