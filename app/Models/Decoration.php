<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected $morphClass = 'em_decorations';

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'em_decoration_event');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $table = 'em_decorations';
}
