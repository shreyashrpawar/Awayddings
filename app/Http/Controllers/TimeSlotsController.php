<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time_slots = TimeSlot::orderBy('id', 'DESC')->get();
        return view('app.time_slots.index',compact('time_slots'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.time_slots.create');
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
            // print_r($request->all());
            $timeEntry = TimeSlot::where('from_time', $request->from_time)->exists();

            if ($timeEntry == false) {
                $time_slot_details = [
                    'from_time' => $request->from_time,
                    'to_time' => $request->to_time,
                    'status' => $request->status
                ];

                $timeSlot_add = TimeSlot::create($time_slot_details);
                $request->session()->flash('success','Successfully Saved');
                return redirect(route('time_slots.index'));
            } else {
                $request->session()->flash('error', 'The selected time slot already exists.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the database operation
            $request->session()->flash('error', 'An error occurred while saving the time slot.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeSlot  $timeSlot
     * @return \Illuminate\Http\Response
     */
    public function show(TimeSlot $timeSlot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeSlot  $timeSlot
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $time_slot = TimeSlot::find($id);
       return view('app.time_slots.edit',compact('time_slot'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeSlot  $timeSlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeSlot $timeSlot)
    {
        try {
            // print_r($request->all());
            $timeEntry = TimeSlot::where('from_time', $request->from_time)->where('id', '<>', $request->time_slot_id)->exists();
            $time_slot = TimeSlot::find($request->time_slot_id);
            // dd($timeEntry);

            if ($timeEntry == false) {
                $time_slot->update([
                    'from_time' => $request->from_time,
                    'to_time' => $request->to_time,
                    'status' => $request->status
                ]);
                $request->session()->flash('success','Successfully Updated');
                return redirect(route('timeslots.index'));
            } else {
                $request->session()->flash('error', 'The selected time slot already exists.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            // Handle any exceptions that might occur during the database operation
            $request->session()->flash('error', 'An error occurred while saving the time slot.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeSlot  $timeSlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeSlot $timeSlot)
    {
        //
    }
}
