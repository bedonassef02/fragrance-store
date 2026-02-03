<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // General classification if needed, but primarily pivot handles this
        'image'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('type')
                    ->withTimestamps();
    }
}
