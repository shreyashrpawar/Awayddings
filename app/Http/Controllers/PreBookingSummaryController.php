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
        //  "_token": "uEjwXdGoiWtOM6AigD3hop1d6mRIcHKwzV4RD72x",
        //"_method": "put",
        //"pre_booking_id": "2",
        //"selected_status": "2",
        //"final_amount": "2145000",
        //"admin_remark": "ss"
        //}
        $status = $request->selected_status;
        $pre_booking_id = $request->pre_booking_id;
        $amount = $request->final_amount;
        $admin_remarks = $request->admin_remark;

        $current_status = PreBookingSummaryStatus::find($pre_booking_id);
        $pre_booking_summary = PreBookingSummary::find($pre_booking_id);
        if($current_status == 'approved'){
            // create a record in the booking

            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));
        }else{
            // update on the existing pre booking

            $pre_booking_summary->update([
                'pre_booking_summary_status_id' => $status,
                'admin_remarks' => $admin_remarks
            ]);
            $request->session()->flash('success','Successfully Updated');
            return redirect(route('pre-bookings.index'));

        }








       return $request->all();
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
