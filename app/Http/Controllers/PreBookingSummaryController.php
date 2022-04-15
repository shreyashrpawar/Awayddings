<?php

namespace App\Http\Controllers;

use App\Models\PreBookingSummary;
use App\Models\PreBookingSummaryStatus;
use Illuminate\Http\Request;

class PreBookingSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking_summary = PreBookingSummary::orderby('id','DESC')->paginate(50);
        return view('app.prebooking.index',compact('booking_summary'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $summary = PreBookingSummary::with(['user','property','pre_booking_details','pre_booking_details.hotel_chargable_type','pre_booking_summary_status'])
            ->find($id);
        $pre_booking_summary_status = PreBookingSummaryStatus::pluck('name','id')->all();
       return view('app.prebooking.show',compact('summary','pre_booking_summary_status'));
//       return response()->json([
//           'success' => true,
//           'message' => 'Success',
//           'data' => $summary
//       ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function edit(PreBookingSummary $preBookingSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreBookingSummary $preBookingSummary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PreBookingSummary  $preBookingSummary
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreBookingSummary $preBookingSummary)
    {
        //
    }
}
