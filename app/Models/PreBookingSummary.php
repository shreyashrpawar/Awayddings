<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreBookingSummary extends Model
{
    use HasFactory,SoftDeletes;
    protected  $fillable = [
        'user_id',
        'property_id',
        'check_in',
        'check_out',
        'total_amount',
        'budget',
        'user_remarks',
        'admin_remarks',
        'status',
        'pax',
        'pre_booking_summary_status_id'
    ];
    protected $dates = ['check_in','check_out'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function property(){
        return $this->belongsTo('App\Models\Property');
    }
    public function pre_booking_details(){
        return $this->hasMany('App\Models\PreBookingDetails','pre_booking_summaries_id','id');
    }
    public function pre_booking_summary_status(){
        return $this->belongsTo('App\Models\PreBookingSummaryStatus','pre_booking_summary_status_id','id');

    }

}
