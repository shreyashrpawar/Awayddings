<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EventPreBookingAddsonArtist extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'em_prebooking_summaries_id',
        'em_addson_artist_id',
        'em_addson_artist_person_id',
        'addson_artist_amount',
        'total_amount',
    ];

    protected $table = 'em_prebooking_addson_artist';

    public function addson_artist_person()
    {
        return $this->belongsTo('App\Models\ArtistPerson', 'em_addson_artist_person_id', 'id');
    }

    public function addson_artist()
    {
        return $this->belongsTo('App\Models\Artist', 'em_addson_artist_id', 'id');
    }
}
