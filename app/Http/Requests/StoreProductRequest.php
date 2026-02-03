<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin check via middleware
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|gt:price',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'featured' => 'boolean',
            'trending' => 'boolean',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            
            // Perfume Attributes
            'concentration' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female,unisex',
            'type' => 'nullable|in:original,local,vintage',
            'inspired_by' => 'nullable|string|max:255',
            'original_product_id' => 'nullable|exists:products,id',

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            
            // Relations
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            
            'notes' => 'nullable|array',
            'notes.*' => 'exists:notes,id',
            
            // Variants
            'variants' => 'nullable|array',
            'variants.*.capacity' => 'required_with:variants|integer|min:1',
            'variants.*.unit' => 'required_with:variants|string|max:10', // ml, oz
            'variants.*.container_type' => 'required_with:variants|string|max:50', // Bottle, Decant
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            
            // Images
            'images' => 'nullable|array',
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,webp|max:51200',
        ];
    }
}
