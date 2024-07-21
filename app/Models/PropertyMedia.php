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

    public function mediaCategory(){
        return $this->belongsTo('App\Models\MediaCategory');
    }
    public function MediaSubCategory(){
        return $this->belongsTo('App\Models\MediaSubCategory');
    }
}
