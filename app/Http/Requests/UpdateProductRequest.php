<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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

            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            
            // Existing/New Variants
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required_with:variants|string|max:50',
            'variants.*.color_id' => 'required_with:variants|exists:colors,id',
            'variants.*.quantity' => 'required_with:variants|integer|min:0',
            
            'deleted_variants' => 'nullable|array',
            
            // New Images
            'new_images' => 'nullable|array',
            'new_images.*.file' => 'required|image|mimes:jpeg,png,jpg,webp|max:51200',
            'new_images.*.color_id' => 'nullable|exists:colors,id',
            
            'deleted_images' => 'nullable|array',
        ];
    }
}
