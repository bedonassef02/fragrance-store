<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'capacity', 'unit', 'container_type', 'price', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
