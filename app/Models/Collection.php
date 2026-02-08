<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collection extends Model implements HasMedia
{
    use HasFactory, \App\Traits\Trackable, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')
            ->singleFile();
            
        $this->addMediaCollection('cover')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(400)
             ->height(400)
             ->format('webp');
             
        $this->addMediaConversion('banner_optimized')
             ->width(1920)
             ->height(400)
             ->format('webp')
             ->performOnCollections('banner');
    }

    public function getImageAttribute($value)
    {
        // The 'image' column usually referred to the banner or cover.
        // Let's check 'banner' collection first as default
        $mediaUrl = $this->getFirstMediaUrl('banner', 'banner_optimized');
        if ($mediaUrl) {
            return $mediaUrl;
        }

        if (!$value) {
            return null; 
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
