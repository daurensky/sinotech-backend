<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'slug',
        'name',
        'origin',
        'bio',
        'description',
    ];
}
