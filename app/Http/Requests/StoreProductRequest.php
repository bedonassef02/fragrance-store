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
            'featured' => 'boolean',
            'trending' => 'boolean',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            
            // Relations
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            
            // Variants
            'variants' => 'nullable|array',
            'variants.*.size' => 'required_with:variants|string|max:50',
            'variants.*.color_id' => 'required_with:variants|exists:colors,id',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            
            // Images (with color association)
            'images' => 'nullable|array',
            'images.*.file' => 'required|image|mimes:jpeg,png,jpg,webp|max:51200',
            'images.*.color_id' => 'nullable|exists:colors,id',
        ];
    }
}
