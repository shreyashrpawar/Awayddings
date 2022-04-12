<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreBookingDetails;
use App\Models\PreBookingSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PreBookingController extends Controller
{
    public function submit(Request  $request){

      $user = auth()->user();
      $user_id = $user->id;
      $user_budget  = $request->user_budget;
      $check_in_date  = Carbon::parse($request->check_in);
      $check_out_date  =  Carbon::parse($request->check_out);
      $total_amount  = $request->total_budget;

      $adults  = $request->adults;
      $property_id = $request->property_id;
      $details = $request->details;
      $user_remark = $request->remarks;
 // return $user_id;
        DB::beginTransaction();

        $temp_data = [
                'user_id' => $user_id,
                'budget' => $user_budget,
                'check_in' => $check_in_date,
                'check_out' => $check_out_date,
                'total_amount' => $total_amount,
                'pax' => $adults,
                'property_id' => $property_id,
                'user_remarks' => $user_remark,
                'status' => 1
        ];
      try {
             $pre_booking_summary = PreBookingSummary::create($temp_data);

      foreach($details as $key => $val){
          $date = $val['date'];
          foreach($val['data'] as $key1 => $data){
              $temp_qty = $data['qty'];
              if($temp_qty > 0) {
                  $temp_chargable_type_id = $data['chargable_type_id'];
                  $temp_qty = $data['selectedQty'];
                  $rate = $data['rate'];
                  $temp_percentage_occupancy = $data['percentage_occupancy'];
                  $temp_data = [
                      'hotel_chargable_type_id' => $temp_chargable_type_id,
                      'qty' => $temp_qty,
                      'rate' => $rate,
                      'date' => Carbon::parse($date),
                      'threshold' => $temp_percentage_occupancy,
                      'pre_booking_summaries_id' => $pre_booking_summary->id
                  ];
                  try {
                      PreBookingDetails::create($temp_data);
                  }
                  catch (Throwable $e) {
                      print_r($temp_data);
                      return $e;
                  }
              }
          }
      }
          DB::commit();
        return response()->json([
           'success' => true,
           'message' => 'Successfully Saved'
        ]);

      } catch (\Exception $e) {
          DB::rollback();
          return $e;
      }

    }
}
