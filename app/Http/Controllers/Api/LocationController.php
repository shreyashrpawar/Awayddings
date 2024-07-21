<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getActiveLocation(){
        try{
            $data = Location::select('id','name')->where('status',1)->get();
            return response()->json([
                'success' => true,
                'message' => 'SUCCESS',
                'data' => $data
            ],200);

       } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'ERROR',
                'data' => $e
            ],500);
        }

    }
}
