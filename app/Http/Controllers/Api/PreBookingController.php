<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendGenericEmail;
use App\Models\BookingSummary;
use App\Models\PreBookingDetails;
use App\Models\PreBookingSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PreBookingController extends Controller
{
    public function submit(Request $request)
    {
        // print_r($request->all()); exit;
        $user = auth()->user();
        $user_id = $user->id;
        $user_budget = $request->user_budget;
        $check_in_date = Carbon::parse($request->check_in);
        $check_out_date = Carbon::parse($request->check_out);
        $total_amount = $request->total_budget;

        $adults = $request->adults;
        $property_id = $request->property_id;
        $details = $request->details;
        $user_remark = $request->remarks;

        $bride_name = $request->bride_name;
        $groom_name = $request->groom_name;

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
            'status' => 1,
            'pre_booking_summary_status_id' => 1,
            'bride_name' => $bride_name,
            'groom_name' => $groom_name,
        ];
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Successfully Saved',
        //     'details' => $details

        // ]);
        // $prebooking = DB::table('pre_booking_summaries')->insert($temp_data);

        try {
            $pre_booking_summary = PreBookingSummary::create($temp_data);

            foreach ($details as $key => $val) {
                $date = $val['date'];
                foreach ($val['data'] as $key1 => $data) {
                    $temp_qty = $data['selectedQty'];
                    if ($temp_qty > 0) {
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
                            'pre_booking_summaries_id' => $pre_booking_summary->id,
                        ];
                        try {
                            PreBookingDetails::create($temp_data);
                        } catch (Throwable $e) {
                            print_r($temp_data);
                            return $e;
                        }
                    }
                }
            }
            DB::commit();

            //prejourney mail trigger
            // $details = ['email' => $user->email, 'mailbtnLink' => '', 'mailBtnText' => '',
            //     'mailTitle' => 'Thank you!', 'mailSubTitle' => 'Your prebooking has been confirmed!', 'mailBody' => 'We are thrilled that you chose us to plan your destination wedding. Our representative will connect with you shortly'];
            // SendGenericEmail::dispatch($details);

            return response()->json([
                'success' => true,
                'message' => 'Successfully Saved',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }

    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $pending_summary = PreBookingSummary::with(['user', 'pre_booking_summary_status', 'property', 'pre_booking_details', 'pre_booking_details.hotel_chargable_type'])
            ->where('user_id', $user_id)->where('pre_booking_summary_status_id', 1)->orderBy('created_at', 'desc')->get();

        $cancelled_summary = PreBookingSummary::with(['user', 'pre_booking_summary_status', 'property', 'pre_booking_details', 'pre_booking_details.hotel_chargable_type'])
            ->where('user_id', $user_id)->whereIn('pre_booking_summary_status_id', [3, 4])->orderBy('created_at', 'desc')->get();

        $approved_summary = BookingSummary::with(['user', 'booking_details', 'booking_details.hotel_chargable_type', 'property', 'booking_payment_summary', 'booking_payment_summary.booking_payment_details'])
            ->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => [
                'pending' => $pending_summary,
                'cancelled' => $cancelled_summary,
                'approved' => $approved_summary,
                'completed' => [],
            ],
        ]);
    }
}
