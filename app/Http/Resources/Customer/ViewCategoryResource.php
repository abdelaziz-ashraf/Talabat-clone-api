<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'vendor_name' => $this->vendor->name,
            'vendor_id' => $this->vendor_id,
            'name' => (string) $this->name,
            'created_at' => $this->created_at
        ];
    }
}
