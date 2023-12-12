<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EventPreBookingDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'em_prebooking_summary_details';

    protected  $fillable = [
        'em_prebooking_summaries_id',
        'date',
        'start_time',
        'end_time',
        'em_artist_person_id',
        'em_decor_id',
        'artist_amount',
        'decor_amount',
        'total_amount',
        'em_event_id',
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function events()
    {
        return $this->belongsTo(Event::class, 'em_event_id', 'id');
    }

    public function artistPerson()
    {
        return $this->belongsTo(ArtistPerson::class, 'em_artist_person_id', 'id');
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class, 'em_decor_id', 'id');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
