<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'vendor_name' => $this->vendor->name,
            'status' => $this['status'],
            'delivery_fee' => (integer) $this['delivery_fee'],
            'products_total_price' => (integer) $this['total_price'],
            'order_total_price' => (integer) $this['total_price'] + $this['delivery_fee'],
            'payment_method' => $this['payment_method'],
            'delivery_address' => $this['delivery_address'],
            'items' => OrderItemsResource::collection($this->items),
            'created_at' => $this['created_at'],
        ];
    }
}
