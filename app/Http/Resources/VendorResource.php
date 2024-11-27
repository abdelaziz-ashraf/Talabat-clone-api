<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer)$this->id,
            'name' => (string) $this->name,
            'image' => (string) $this->image,
            'addresses' => AddressResource::collection($this->addresses),
            'creates_at' => $this->created_at
        ];
    }
}
