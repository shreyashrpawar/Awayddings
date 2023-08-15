<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCustomerBookingInvoice extends Model
{
    use HasFactory;

    protected $table = 'em_customer_booking_invoices';

    use HasFactory;
    protected $fillable = [
        'em_booking_summary_id',
        'invoice_url'
    ];
}
