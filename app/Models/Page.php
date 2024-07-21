<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    
    protected $table = 'pages';

    // Optional: Define fillable attributes if you plan to use mass assignment
    protected $fillable = [
        'name',
        'display_order',
    ];
   
}
