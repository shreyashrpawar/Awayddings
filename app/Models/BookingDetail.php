<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_summaries_id',
        'date',
        'hotel_chargable_type_id',
        'rate',
        'qty',
        'threshold'
    ];

    protected $dates= ['date'];

    public function hotel_chargable_type(){
        return $this->belongsTo('App\Models\HotelChargableType','hotel_chargable_type_id','id');
    }
}
