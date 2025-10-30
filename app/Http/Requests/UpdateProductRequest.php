<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 1. Get the Product model instance from the route
        $product = $this->route('product'); 
        
        // 2. Check if the authenticated user is the merchant of this product
        return $product && $product->merchant_id === Auth::id();
    }

    public function rules(): array
    {
        // Get the current product ID to ignore it during the unique check for the UUID
        $productId = $this->route('product')->id ?? null;
        
        return [
            // UUID is updated only sometimes and must be unique, ignoring the current product
            'uuid'          => ['sometimes', 'string', Rule::unique('products', 'uuid')->ignore($productId)], 
            'category_id'   => ['sometimes', 'exists:categories,id'],
            'image_id'      => ['nullable', 'sometimes', 'exists:images,id'],
            'name'          => ['sometimes', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'stock'         => ['sometimes', 'integer', 'min:0'],
            'price'         => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}