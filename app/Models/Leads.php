<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $table = 'leads_capture';
    protected  $fillable = ['name','email', 'mobile', 'wedding_date', 'pax', 'status', 'origin', 'bride_groom', 'remarks'];
}
