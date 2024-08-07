<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'transaction_id',
        'payment_status',
        'meta',
        'providerReferenceId',
        'merchantOrderId',
        'checksum',
        'booking_payment_summaries_id',
        'installment_no',
        'payment_mode',
    ];

    public function bookingPaymentDetail()
    {
        return $this->belongsTo(BookingPaymentDetail::class, 'booking_payment_summaries_id', 'booking_payment_summaries_id');
    }
}