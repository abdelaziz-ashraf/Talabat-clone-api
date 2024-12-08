<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this['status'],
            'total_price' => $this['total_price'] + $this['delivery_fee'],
            'vendor_name' => $this->vendor->name,
            'created_at' => $this['created_at'],
        ];
    }
}
