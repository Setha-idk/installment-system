<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Must include 'name' which your JavaScript reads
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'description' => $this->description,
        ];
    }
}