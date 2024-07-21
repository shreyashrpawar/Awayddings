<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyRate extends Model
{
    use HasFactory;

    protected $fillable = [
       'id',
        'property_id',
        'hotel_chargable_type_id',
        'date',
        'amount',
        'available',
        'sold',
        'block',
        'occupancy_percentage',
        'status'
    ];
    protected  $dates = [
        'date'
    ];

    public function hotel_chargable_type(){
        return $this->belongsTo('App\Models\HotelChargableType');
    }
}
