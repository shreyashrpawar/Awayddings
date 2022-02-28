<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected  $fillable  = [
       'name',
       'alias_name',
       'featured_image',
       'location_id',
       'description',
       'gmap_embedded_code',
       'status',
    ];
}
