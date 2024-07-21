<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pin_code',
        'gst',
        'status',
        'pan',
        'gst_file',
        'pan_card_file',
        'cancelled_cheque_file'
    ];
}
