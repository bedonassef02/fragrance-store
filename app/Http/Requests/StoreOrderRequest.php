<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $phone = $this->phone;
            
            // Remove all non-numeric characters
            $phone = preg_replace('/[\D]/', '', $phone);
            
            // Normalize Egyptian numbers
            // If starts with 20 (country code), remove it
            if (str_starts_with($phone, '20')) {
                $phone = substr($phone, 2);
            }
            // If starts with 0020, remove it
            elseif (str_starts_with($phone, '0020')) {
                $phone = substr($phone, 4);
            }
            
            $this->merge([
                'phone' => $phone,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'          => 'required|email|max:255',
            'first_name'     => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'last_name'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'address'        => 'required|string|max:255',
            'city'           => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'phone'          => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/'],
            'payment_method' => 'required|in:cod,card,wallet,fawry',
        ];
    }
}
