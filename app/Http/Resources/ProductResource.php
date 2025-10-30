<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray(Request $request): array
    {
        return [
            'uuid'          => $this->uuid,
            'name'          => $this->name,
            'description'   => $this->description,
            'stock'         => (int) $this->stock,
            'price'         => (float) $this->price, // FIX: Ensure price is a float/decimal to fix $NaN
            
            // --- FIXES BELOW: Include Relationships ---
            
            'category'      => $this->whenLoaded('category', function () {
                // Must return a CategoryResource instance
                return new CategoryResource($this->category); 
            }),

            'plans'         => $this->whenLoaded('plans', function () {
                return $this->plans->map(fn($plan) => [
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'installments_count' => $plan->installments_count,
                    'interest_rate' => $plan->interest_rate
                ]);
            }),
            
            'image'         => $this->whenLoaded('image', function () {
                // Must return an ImageResource instance
                return new ImageResource($this->image); 
            }),

            'plans'         => $this->whenLoaded('plans', function () {
                // Must return a PlanResource Collection (array of plans)
                return PlanResource::collection($this->plans); 
            }),

            'merchant_id'   => $this->merchant_id,
            'created_at'    => $this->created_at,
        ];
    }
}