<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSummary extends Model
{
    use HasFactory;
    protected $fillable = [
       'user_id',
       'pre_booking_summary_id',
       'property_id',
       'check_in',
       'check_out',
       'total_amount',
       'amount',
       'discount',
       'pax',
       'user_remarks',
       'admin_remarks',
       'status',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function property(){
        return $this->belongsTo('App\Models\Property');
    }

    public function booking_details(){
        return $this->hasMany('App\Models\BookingDetail','booking_summaries_id','id');
    }

    public function booking_payment_summary(){
        return $this->hasOne('App\Models\BookingPaymentSummary','booking_summaries_id','id');
    }

    public function booking_invoice(){
        return $this->belongsTo('App\Models\CustomerBookingInvoice','id','booking_summary_id');
    }

    protected $dates = ['check_in','check_out'];
}