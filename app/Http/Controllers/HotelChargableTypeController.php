<?php

namespace App\Http\Controllers;

use App\Models\HotelChargableType;
use Illuminate\Http\Request;

class HotelChargableTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\HotelChargableType  $hotelChargableType
     * @return \Illuminate\Http\Response
     */
    public function show(HotelChargableType $hotelChargableType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HotelChargableType  $hotelChargableType
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelChargableType $hotelChargableType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HotelChargableType  $hotelChargableType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelChargableType $hotelChargableType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HotelChargableType  $hotelChargableType
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelChargableType $hotelChargableType)
    {
        //
    }

    public function getAllPropertyChargableCategory(){
        $data = HotelChargableType::where('status',1)

                ->get();

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $data
        ]);
    }
}
