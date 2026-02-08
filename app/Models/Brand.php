<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'is_luxury',
        'is_local'
    ];

    protected $casts = [
        'is_luxury' => 'boolean',
        'is_local' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
