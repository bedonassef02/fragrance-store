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

    protected function prepareForValidation()
    {
        $keys = ['category', 'brand', 'notes', 'capacity', 'concentration', 'price_range'];
        $inputs = [];

        foreach ($keys as $key) {
            if ($this->has($key) && is_string($this->input($key))) {
                $inputs[$key] = explode(',', $this->input($key));
            }
        }

        $this->merge($inputs);
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
            'brand'            => 'nullable|array',
            'brand.*'          => 'string|max:50',
            'notes'           => 'nullable|array',
            'notes.*'         => 'string|max:50',
            'capacity'        => 'nullable|array', // Added
            'capacity.*'      => 'integer|min:1',  // Added
            'concentration'   => 'nullable|array', // Added
            'concentration.*' => 'string|max:50',  // Added
            'in_stock'         => 'nullable|boolean',
            'sort'             => ['nullable', 'string', 'regex:/^[\w\-,]+$/'],
        ];
    }
}
