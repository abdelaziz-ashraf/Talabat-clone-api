<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryUserResource extends JsonResource
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
            'name' => $this['name'],
            'email' => $this['email'],
            'phone' => $this['phone'],
            'vehicle_type' => $this['vehicle_type'],
            'vehicle_number' => $this['vehicle_number'],
            'status' => $this['status'],
            'created_at' => $this['created_at'],
        ];
    }
}
