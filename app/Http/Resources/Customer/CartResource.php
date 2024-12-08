<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPrice = $this->items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        return [
            'id' => $this['id'],
            'vendor_name' => $this->vendor->name,
            'items' => CartItemResource::collection($this->items),
            'total_price' => $totalPrice,
            'created_at' => $this->created_at
        ];
    }
}
