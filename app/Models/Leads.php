<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    protected $table = 'leads_capture';
    protected  $fillable = ['name','email', 'mobile', 'wedding_date', 'pax', 'status', 'origin', 'bride_groom', 'remarks'];
    use SoftDeletes;
    

    const STATUS_OPTIONS = [
        'recce_planned',
        'recce_done',
        'under_discussion',
        'booked',
        'lost_general_inquiry',
        'call_not_picked',
        'call_back',
        'send_to_decor'
    ];
}
