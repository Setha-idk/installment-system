<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only authenticated users (merchants) can create products
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'uuid'          => ['required', 'string', 'unique:products,uuid'], // Required to be unique
            'category_id'   => ['required', 'exists:categories,id'],
            'image_id'      => ['nullable', 'exists:images,id'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'stock'         => ['required', 'integer', 'min:0'], // Must be >= 0
            'price'         => ['required', 'numeric', 'min:0', 'max:999999.99'], // Max based on decimal(8, 2)
        ];
    }
}