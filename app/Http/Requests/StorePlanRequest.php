<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Checks if the authenticated user owns the product they are attaching the plan to.
     */
    public function authorize(): bool
    {
        // Must be authenticated
        if (!Auth::check()) {
            return false;
        }

        // Check ownership of the product_id submitted in the request
        if ($productId = $this->input('product_id')) {
            return Product::where('id', $productId)
                          ->where('merchant_id', Auth::id())
                          ->exists();
        }

        // If product_id is somehow missing (though it's required in rules),
        // we'll still let it pass to the rules() method to fail there.
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     * All fields required for creation.
     */
    public function rules(): array
    {
        return [
            // Must be required as you must link a plan to a product at creation
            'product_id'         => ['required', 'integer', 'exists:products,id'], 
            'name'               => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'installments_count' => ['required', 'integer', 'min:1'],
            'interest_rate'      => ['required', 'numeric', 'min:0', 'max:99.99'],
        ];
    }
}