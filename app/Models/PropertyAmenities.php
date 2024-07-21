<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenities extends Model
{
    use HasFactory;
    protected  $fillable = [
        'property_id',
        'hotel_facility_id',
    ];

    public function hotel_facility(){
        return $this->belongsTo('App\Models\HotelFacility','hotel_facility_id','id');
    }
}
