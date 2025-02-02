<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorsSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer)$this->vendor_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitud,
            'name' => (string) $this->name,
            'image' => (string) $this->image,
        ];
    }
}
