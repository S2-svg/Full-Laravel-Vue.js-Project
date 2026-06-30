<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_percent' => $this->discount_percent,
            'discount_start_at' => $this->discount_start_at,
            'discount_end_at' => $this->discount_end_at,
            'stock' => $this->stock,
            'image' => $this->image,
            'final_price' => $this->final_price,
            'has_discount' => $this->has_discount,
            'discount_status' => $this->discount_status,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
