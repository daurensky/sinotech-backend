<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $values = [
            'slug' => $this->slug,
            'name' => $this->name,
            'image' => $this->media[0]?->original_url,
        ];

        if ($this->relationLoaded('products')) {
            $values['products'] = ProductResource::collection($this->products);
        }

        return $values;
    }
}
