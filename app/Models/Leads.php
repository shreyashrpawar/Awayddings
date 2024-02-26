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
        'recce_planned' => [
            'background' => '#7cfffc',
            'badge' => 'badge-info',
        ],
        'recce_done' => [
            'background' => '#7cfffc',
            'badge' => 'badge-info',
        ],
        'under_discussion' => [
            'background' => '#ffea99',
            'badge' => 'badge-warning',
        ],
        'booked' => [
            'background' => '#b9fd84',
            'badge' => 'badge-success',
        ],
        'lost_general_inquiry' => [
            'background' => '#ff8989',
            'badge' => 'badge-danger',
        ],
        'call_not_picked' => [
            'background' => 'lightsteelblue',
            'badge' => 'badge-secondary',
        ],
        'call_back' => [
            'background' => 'lightgreen',
            'badge' => 'badge-success',
        ],
        'send_to_decor' => [
            'background' => 'lightgreen',
            'badge' => 'badge-success',
        ],
    ];
}
