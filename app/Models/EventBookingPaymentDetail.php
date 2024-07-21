<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class EventBookingPaymentDetail extends Model
{
    use HasFactory;

    protected $table = 'em_booking_payment_details';

    protected $fillable = [
        'em_booking_payment_summaries_id',
        'installment_no',
        'amount',
        'paid',
        'date',
        'installment_no',
        'due',
        'status',
    ];
}
