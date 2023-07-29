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
        'artist_id',
        'status',
    ];

    protected $table = 'artist_persons';//lightand_sounds
}
