<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'media_category_id',
        'media_sub_category_id',
        'media_url',
        'media_meta_data',
    ];
}
