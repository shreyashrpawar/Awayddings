<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class EmAddonFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];


    public function facilityDetails()
    {
        return $this->hasMany(EmAddonFacilityDetails::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $table = 'em_addon_facility';
}
