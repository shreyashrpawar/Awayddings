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
        'payment_mode',
    ];

    public function bookingPaymentDetail()
    {
        return $this->hasMany(BookingPaymentDetail::class, 'transaction_id', 'transaction_id');

    }
}