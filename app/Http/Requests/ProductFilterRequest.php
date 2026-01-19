<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable', // Can be array or string
            'price_range' => 'nullable', // Array
            'sizes' => 'nullable', // Array or string
            'sort' => 'nullable|in:price_asc,price_desc,newest',
        ];
    }
}
