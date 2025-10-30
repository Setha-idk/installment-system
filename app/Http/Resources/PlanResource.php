<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Must include fields read by your JavaScript, like installments_count and interest_rate
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'installments_count' => (int) $this->installments_count,
            'interest_rate'      => (float) $this->interest_rate, 
            'product_id'         => $this->product_id,
        ];
    }
}