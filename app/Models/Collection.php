<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'subtitle', 'image', 'route', 'cta_text', 'cta_class', 'layout_class', 'sort_order'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
