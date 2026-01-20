<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'product_title' => 'nullable|string', 
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1'
        ];
    }
}
