<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmAddonFacility extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'em_addon_facility';

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    public function facilityDetails(): HasMany
    {
        return $this->hasMany(EmAddonFacilityDetails::class);
    }
}
