<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $table = 'em_artist_persons';//lightand_sounds

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}