<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class BookingPaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_payment_summaries_id',
        'installment_no',
        'active_installment', 
        'amount',
        'payment_mode',
        'remarks',
        'paid',
        'date',
        'due',
        'status',
        'email_sent',
        'transaction_id',
        'transaction_status'
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'booking_payment_summaries_id', 'id');
    }

}

