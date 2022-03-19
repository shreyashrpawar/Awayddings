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
        'address',
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

    public function images(){
        return  $this->hasMany('App\Models\PropertyMedia','property_id','id')->where('media_category_id',1);
    }
    public function pdfs(){
        return  $this->hasMany('App\Models\PropertyMedia','property_id','id')->where('media_category_id',3);
    }

    public function videos(){
        return  $this->hasMany('App\Models\PropertyMedia','property_id','id')
                                ->where('media_category_id',2);
    }
}
