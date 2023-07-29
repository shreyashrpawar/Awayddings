<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LightandSound extends Model
{
    use HasFactory;

    protected $table = 'lightandsounds';//lightand_sounds

    protected $fillable = [
        'status',
    ];

    protected $morphClass = 'lightandsound';

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
