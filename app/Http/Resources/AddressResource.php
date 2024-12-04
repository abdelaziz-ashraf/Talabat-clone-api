<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'active' => (boolean) (is_null($this->active) ? true : $this->active),
            'address' => (string) $this->address,
            'city' => (string) $this->city,
            'longitude' => (float) $this->longitude,
            'latitude' => (float) $this->latitude,
            'created_at' => $this->created_at
        ];
    }
}
