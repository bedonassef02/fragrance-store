<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, \App\Traits\Trackable, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useFallbackUrl(asset('images/placeholder.jpg'));

        $this->addMediaCollection('scents');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->format('webp');

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(800)
            ->format('webp');

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->format('webp');
    }

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'image',
        'badge',
        'badge_color',
        'featured',
        'trending',
        'meta_title',
        'meta_description',
        'keywords',
        'concentration',
        'gender',
        'type',
        'inspired_by',
        'original_product_id'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'trending' => 'boolean',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
    ];

    public function getImageAttribute($value)
    {
        // 1. Check Media Library first
        $mediaUrl = $this->getFirstMediaUrl('default', 'medium');
        if ($mediaUrl) {
            return $mediaUrl;
        }

        // 2. Fallback to legacy column
        if (!$value) {
            return asset('images/placeholder.jpg'); // Or null
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class)
            ->withPivot('type')
            ->withTimestamps();
    }

    public function originalProduct()
    {
        return $this->belongsTo(Product::class, 'original_product_id');
    }

    public function inspiredProducts()
    {
        return $this->hasMany(Product::class, 'original_product_id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function isOutOfStock()
    {
        return $this->variants->sum('quantity') <= 0;
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('trending', true);
    }

    /**
     * Get all gallery images (Media Library + legacy)
     */
    public function getGalleryImagesAttribute()
    {
        $gallery = collect();

        // 1. Media Library (excluding the main image if it's the first one, or handled via logic)
        // Actually, let's just dump all media items from 'default' collection
        $mediaItems = $this->getMedia('default');
        
        foreach ($mediaItems as $media) {
            $gallery->push((object)[
                'url' => $media->getUrl('medium'), // View image
                'thumb' => $media->getUrl('thumb'), // Thumbnail
                'original' => $media->getUrl(), // Full size
                'id' => $media->id,
                'is_legacy' => false
            ]);
        }

        // 2. Legacy Images
        foreach ($this->images as $img) {
            $gallery->push((object)[
                'url' => asset('storage/' . $img->image_path),
                'thumb' => asset('storage/' . $img->image_path),
                'original' => asset('storage/' . $img->image_path),
                'id' => $img->id,
                'is_legacy' => true
            ]);
        }
        
        // Ensure main image is included if not present (optional, but good for gallery)
        // If gallery is empty, add main image
        if ($gallery->isEmpty() && $this->image) {
             $gallery->push((object)[
                'url' => $this->image,
                'thumb' => $this->image,
                'original' => $this->image,
                'id' => 0, // Placeholder ID
                'is_legacy' => true
            ]);
        }

        return $gallery;
    }

    /**
     * Get the display price (lowest variant price or base price)
     */
    public function getDisplayPriceAttribute()
    {
        // If variants exist, return the minimum variant price (excluding samples and decants)
        if ($this->variants->isNotEmpty()) {
            // Filter out samples (2ml) and decants
            $nonSampleVariants = $this->variants->filter(function ($variant) {
                $unit = strtolower($variant->unit ?? '');
                $containerType = strtolower($variant->container_type ?? '');

                return $variant->capacity != '2' &&
                    !str_contains($unit, 'sample') &&
                    !str_contains($unit, 'decant') &&
                    !str_contains($containerType, 'decant');
            });

            // If we have non-sample/non-decant variants, use their minimum price
            if ($nonSampleVariants->isNotEmpty()) {
                return $nonSampleVariants->min('price');
            }

            // Otherwise, fall back to all variants minimum
            return $this->variants->min('price');
        }

        // Otherwise, return the base product price
        return $this->price;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->orderBy('created_at', 'desc');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
}
