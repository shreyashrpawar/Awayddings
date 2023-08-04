<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class EmAddonFacilityDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
        'status',
    ];

    protected $morphClass = 'em_addon_facility_details';

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    
    public function facility()
    {
        return $this->belongsTo(EmAddonFacility::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $table = 'em_addon_facility_details';
}
