<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPropertyAlignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'vendor_id'
    ];
    public function property(){
        return $this->belongsTo('App\Models\Property');
    }
}
