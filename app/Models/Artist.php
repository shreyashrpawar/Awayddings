<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
    ];

    protected $morphClass = 'artist';

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function artist_person(){
        return $this->hasMany('App\Models\ArtistPerson','artist_id','id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];
}
