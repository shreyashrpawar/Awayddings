<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    use HasFactory;
    protected  $fillable = ['name','status','description'];

    public function property()
    {
        return $this->hasMany('App\Models\Property')->where('status', 1);
    }
}
