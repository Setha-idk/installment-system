<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            // Prepend a public URL to the stored path for mobile consumption
            'url'   => Storage::url($this->path), 
            'path'  => $this->path, // Original path for internal reference
        ];
    }
}