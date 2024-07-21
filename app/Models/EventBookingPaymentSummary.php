<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class EventBookingPaymentSummary extends Model
{
    use HasFactory;

    protected $table = 'em_booking_payment_summaries';

    protected $fillable = [
        'em_booking_summaries_id',
        'installment_no',
        'paid',
        'discount',
        'amount',
        'payment_mode',
        'remarks',
        'status',
    ];
    public function booking_payment_details(){
        return $this->hasMany('App\Models\EventBookingPaymentDetail','em_booking_payment_summaries_id','id');
    }
}
