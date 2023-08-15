<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPreBookingSummary extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'em_prebooking_summaries';

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
        'pre_booking_summary_status_id',
        'bride_name',
        'groom_name'
    ];
    protected $dates = ['check_in','check_out'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function property(){
        return $this->belongsTo('App\Models\Property');
    }
    public function event_pre_booking_details(){
        return $this->hasMany('App\Models\EventPreBookingDetails','em_prebooking_summaries_id','id');
    }
    public function pre_booking_summary_status(){
        return $this->belongsTo('App\Models\PreBookingSummaryStatus','pre_booking_summary_status_id','id');

    }
    public function event_pre_booking_addson_details()
    {
        return $this->hasMany('App\Models\EventPreBookingAddsonDetails', 'em_prebooking_summaries_id', 'id');
    }

    public function event_pre_booking_addson_artist_person()
    {
        return $this->hasMany('App\Models\EventPreBookingAddsonArtist', 'em_prebooking_summaries_id', 'id'); // Load the related artistPerson
    }



    
    // public function bookingSummary()
    // {
    //     return $this->belongsTo(App\Models\BookingSummary::class,'booking_summary_id');
    // }
}
