<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $table = 'em_artists';

    protected $morphClass = 'em_artists';

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function persons(): HasMany
    {
        return $this->hasMany(ArtistPerson::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'em_artist_event');
    }
}
