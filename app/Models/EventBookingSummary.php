<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventBookingSummary extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'em_booking_summaries';

    protected  $fillable = [
        'user_id',
        'em_prebooking_summaries_id',
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
        'booking_summaries_status',
        'booking_summaries_status_remarks',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function property(){
        return $this->belongsTo('App\Models\Property');
    }

    public function booking_details(){
        return $this->hasMany('App\Models\EventBookingDetail','em_booking_summaries_id','id');
    }

    public function booking_payment_summary(){
        return $this->hasOne('App\Models\EventBookingPaymentSummary','em_booking_summaries_id','id');
    }

    public function booking_invoice(){
        return $this->belongsTo('App\Models\EventCustomerBookingInvoice','id','em_booking_payment_summaries_id');
    }

    protected $dates = ['check_in','check_out'];

    public function bookingPaymentDetails()
    {
        return $this->hasOne(App\Models\EventBookingPaymentDetail::class, 'em_booking_payment_summaries_id');
    }

    public function bookingAddsonDetails()
    {
        return $this->hasMany('App\Models\EventBookingAddsonDetails', 'em_booking_summaries_id', 'id');
    }

    public function bookingAddsonArtistPerson()
    {
        return $this->hasMany('App\Models\EventBookingAddsonArtist', 'em_booking_summaries_id', 'id'); // Load the related artistPerson
    }
}
