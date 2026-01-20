<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search'           => 'nullable|string|max:100',
            'collection'       => 'nullable|string|exists:collections,slug',
            'category'         => 'nullable|array',
            'category.*'       => 'string|max:50',
            'price_range'      => 'nullable|array',
            'price_range.*'    => ['string', 'regex:/^\d+-\d+$|^\d+\+$/'],
            'sizes'            => 'nullable|array',
            'sizes.*'          => 'string|max:20',
            'colors'           => 'nullable|array',
            'colors.*'         => 'string|max:50',
            'in_stock'         => 'nullable|boolean',
            'sort'             => 'nullable|string|in:price_asc,price_desc,newest',
        ];
    }
}
