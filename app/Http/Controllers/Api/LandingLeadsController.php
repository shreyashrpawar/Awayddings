<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendGenericEmail;
use App\Jobs\SendLeadGenerationEmail;
use App\Models\Leads;
use App\Models\User;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class LandingLeadsController extends Controller
{

   public function store(Request  $request){

        $data = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_mobile' => $request->customer_mobile,
            'couple_name' => $request->couple_name,
            'customer_date' => $request->customer_date,
            'customer_pax' => $request->customer_pax,
        ];

        $db_data = array(
            'name' => $request->customer_name,
            'email' => $request->customer_email,
            'mobile' => $request->customer_mobile,
            'bride_groom' => $request->couple_name,
            'wedding_date' => $request->customer_date,
            'pax' => $request->customer_pax,
            'status' => 'new',
            'origin' => 'google_ads'
        );

        if ($request->has('customer_mobile')) {
            // Check if mobile already exists
            $existingLead = Leads::where('mobile', $request->customer_mobile)->first();
            if ($existingLead) {
                // Update existing lead
                $existingLead->update($db_data);
            } else {
                // Create new lead
                Leads::create($db_data);
            }
        }

       SendLeadGenerationEmail::dispatch($data);

       return response()->json([
           'success' => true,
           'message' => 'Successfully Saved',
       ]);
   }
   public function captureMobile(Request $request)
   {
       // Get the mobile number from the request
       $mobileNumber = $request->input('mobile_number');
   
       // Find the lead record with the given mobile number
       $lead = Leads::where('mobile', $mobileNumber)->first();

       if ($lead) {
           // Create a new lead with the same details as the existing one
           $leadData = [
               'mobile' => $mobileNumber,
               'name' => $lead->name,
               'email' => $lead->email,
               'bride_groom' => $lead->bride_groom,
               'wedding_date' => $lead->wedding_date,
               'pax' => $lead->pax,
               'status' => 'new',
               'origin' => 'google_ads'
           ];
       
       } else {
           // If the lead doesn't exist, create a new one with the provided mobile number
           $leadData = [
               'mobile' => $mobileNumber,
               'name' => 'Not provided',
               'email' => 'Not provided',
               'bride_groom' => 'Not provided',
               'wedding_date' => '1000-01-01',
               'pax' => 'Not provided',
               'status' => 'new',
               'origin' => 'google_ads'
           ];
       }
   
       // Create or update the lead record
       Leads::updateOrCreate(['mobile' => $mobileNumber], $leadData);
   
       return response()->json([
           'success' => true,
           'message' => 'Mobile number captured successfully',
       ]);
   }
}
