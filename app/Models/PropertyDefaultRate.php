<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDefaultRate extends Model
{
    use HasFactory;
    protected  $fillable = [
        'property_id',
        'hotel_charagable_type_id',
        'date',
        'amount',
        'qty',
        'chargable_percentage',
        'argument',
    ];

    public function hotel_charagable_type(){
        return $this->belongsTo('App\Models\HotelChargableType');
    }
}
