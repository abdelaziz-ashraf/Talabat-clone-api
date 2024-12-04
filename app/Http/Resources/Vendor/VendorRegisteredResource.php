<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\AddressResource;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorRegisteredResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile_details' => VendorResource::make($this),
            'addresses' => AddressResource::collection($this->addresses),
        ];
    }
}
