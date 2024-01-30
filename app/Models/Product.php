<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'short_description',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
