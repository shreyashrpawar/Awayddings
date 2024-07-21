<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBookingInvoice extends Model
{
    protected $table = 'customer_booking_invoices';

    use HasFactory;
    protected $fillable = [
        'booking_summary_id',
        'invoice_url'
    ];
   
}
