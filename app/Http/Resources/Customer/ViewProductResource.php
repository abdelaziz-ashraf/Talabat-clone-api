<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'vendor_id' => $this->category->vendor_id,
            'vendor_name' => $this->category->vendor->name,
            'name' => $this['name'],
            'description' => $this['description'],
            'price' => $this['price'],
            'image' => $this['image'],
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'created_at' => $this['created_at'],
        ];
    }
}
