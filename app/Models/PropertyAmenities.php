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
}
