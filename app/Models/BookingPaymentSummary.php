<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPaymentSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_summaries_id',
        'installment_no',
        'paid',
        'discount',
        'amount',
        'payment_mode',
        'remarks',
        'status'
    ];
    public function booking_payment_details(){
        return $this->hasMany('App\Models\BookingPaymentDetail','booking_payment_summaries_id','id');
    }
}
