<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EmAddonFacilityDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $table = 'em_addon_facility_details';
    protected string $morphClass = 'em_addon_facility_details';

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(EmAddonFacility::class);
    }

}
