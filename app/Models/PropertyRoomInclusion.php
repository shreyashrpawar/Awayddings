<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyRoomInclusion extends Model
{
    use HasFactory;
    protected  $fillable = [
        'property_id',
        'hotel_facility_id',
    ];
    public function room_inclusion(){
        return $this->hasOne('App\Models\PropertyRoomInclusion','hotel_facility_id','id');
    }
}
