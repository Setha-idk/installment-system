<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Checks if the authenticated user owns the plan being updated.
     */
    public function authorize(): bool
    {
        // Must be authenticated
        if (!Auth::check()) {
            return false;
        }

        // 1. Check ownership of the Plan model fetched via Route Model Binding
        $plan = $this->route('plan'); 
        
        if (!$plan || $plan->merchant_id !== Auth::id()) {
            return false;
        }

        // 2. Also check if the merchant owns the new product_id, if one is provided
        if ($productId = $this->input('product_id')) {
            return Product::where('id', $productId)
                          ->where('merchant_id', Auth::id())
                          ->exists();
        }

        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     * Uses 'sometimes' to allow partial updates (e.g., just updating the name).
     */
    public function rules(): array
    {
        return [
            // Use 'sometimes' - if this field is present, validate it.
            'product_id'         => ['sometimes', 'integer', 'exists:products,id'], 
            'name'               => ['sometimes', 'string', 'max:255'],
            // 'description' allows null, so no need for 'sometimes' unless you want to ignore it if absent
            'description'        => ['nullable', 'string'], 
            'installments_count' => ['sometimes', 'integer', 'min:1'],
            'interest_rate'      => ['sometimes', 'numeric', 'min:0', 'max:99.99'],
        ];
    }
}