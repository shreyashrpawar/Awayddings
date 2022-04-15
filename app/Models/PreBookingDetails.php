<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreBookingDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected  $fillable = [
        'pre_booking_summaries_id',
        'date',
        'hotel_chargable_type_id',
        'threshold',
        'rate',
        'qty',
    ];
    protected $dates= ['date'];

    public function hotel_chargable_type(){
        return $this->belongsTo('App\Models\HotelChargableType','hotel_chargable_type_id','id');
    }





}
