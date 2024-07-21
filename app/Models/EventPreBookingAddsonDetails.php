<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPreBookingAddsonDetails extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'em_prebooking_summaries_id',
        'em_addon_facility_id',
        'facility_details_id',
        'total_amount',
    ];

    protected $table = 'em_prebooking_addson_details';

    public function addson_facility()
    {
        return $this->belongsTo('App\Models\EmAddonFacility', 'em_addon_facility_id', 'id');
    }

    public function addson_facility_details()
    {
        return $this->belongsTo('App\Models\EmAddonFacilityDetails', 'facility_details_id', 'id');
    }

}
