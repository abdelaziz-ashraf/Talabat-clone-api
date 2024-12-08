<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->product;
        return [
            'product_name' => $product->name,
            'quantity' => $this->quantity,
            'unit_price' => $product->price,
            'total_price' => $product->price * $product->quantity,
        ];
    }
}
