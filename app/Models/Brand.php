<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class Brand extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->useFallbackUrl(asset('images/placeholder-brand.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(200)
             ->height(200)
             ->format('webp');
    }

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'is_luxury',
        'is_local'
    ];

    public function getImageAttribute($value)
    {
        // 1. Check Media Library first
        $mediaUrl = $this->getFirstMediaUrl('logo', 'thumb');
        if ($mediaUrl) {
            return $mediaUrl;
        }

        // 2. Fallback to legacy column
        $value = trim($value ?? '');
        
        if (!$value) {
            return 'https://placehold.co/200x200?text=No+Logo';
        }

        if (\Illuminate\Support\Str::startsWith(strtolower($value), ['http://', 'https://'])) {
            return $value;
        }
        
        return asset('storage/' . $value);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
