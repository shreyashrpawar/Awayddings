<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ArtistPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'artist_person_link',
        'artist_id',
        'status',
    ];

    protected $table = 'em_artist_persons';

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}
