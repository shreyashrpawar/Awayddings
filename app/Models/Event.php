<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_artist_visible',
        'is_decor_visible',
        'status',
    ];

    // public function decoration(){
    //     return $this->hasMany('App\Models\Decoration','event_id','id');
    // }

    public function artists()
    {
        return $this->belongsToMany(Artist::class);
    }

    public function decorations()
    {
        return $this->belongsToMany(Decoration::class);
    }

    protected $casts = [
        'is_artist_visible' => 'boolean',
        'is_decor_visible' => 'boolean',
        'status' => 'boolean'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
