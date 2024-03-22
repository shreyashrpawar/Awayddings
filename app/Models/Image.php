<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url'];
    protected $table = 'em_images';

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

}
