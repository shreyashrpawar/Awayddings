<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_payment_summaries_id',
        'installment_no',
        'amount',
        'paid',
        'date',
        'installment_no',
        'due',
        'status',
    ];

}
