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
           'customer_location' => $request->customer_location,
           'customer_date' => $request->customer_date,
           'customer_pax' => $request->customer_pax,
       ];

       $db_data = array(
           'name' => $request->customer_name,
           'email' => $request->customer_email,
           'mobile' => $request->customer_mobile,
           'bride_groom' => $request->customer_location,
           'wedding_date' => $request->customer_date,
           'pax' => $request->customer_pax,
           'status' => 'new',
           'origin' => 'google_ads'
       );

       Leads::create($db_data);

       SendLeadGenerationEmail::dispatch($data);

       return response()->json([
           'success' => true,
           'message' => 'Successfully Saved',
       ]);
   }


}
