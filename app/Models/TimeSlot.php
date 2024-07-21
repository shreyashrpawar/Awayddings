<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_time',
        'to_time',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $table = 'em_time_slots';
}
