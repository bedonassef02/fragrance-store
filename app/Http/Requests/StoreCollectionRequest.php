<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin middleware handles auth
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:collections,title',
            'slug' => 'nullable|string|max:255|unique:collections,slug',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:51200', // 50MB Max
            'route' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:50',
            'cta_class' => 'nullable|string|max:255',
            'layout_class' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
        ];
    }
}
