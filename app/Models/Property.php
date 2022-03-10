<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected  $fillable  = [
       'name',
       'alias_name',
       'featured_image',
       'location_id',
       'description',
       'gmap_embedded_code',
       'status',
    ];

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function default_rates(){
        return $this->hasMany('App\Models\PropertyDefaultRate','property_id','id');
    }

    public function amenities(){
        return $this->hasMany('App\Models\PropertyAmenities','property_id','id');
    }

    public function room_inclusions(){
        return $this->hasMany('App\Models\PropertyRoomInclusion','property_id','id');
    }
}
