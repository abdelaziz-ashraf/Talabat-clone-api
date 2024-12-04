<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\VendorsSearchResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Address;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VendorController extends Controller
{
    public function index(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = request('radius', 100);

        $cacheKey = "vendors_nearby:lat={$latitude}:long={$longitude}:radius={$radius}";
        $vendors = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($latitude, $longitude, $radius) {
            $addressableIds = Address::vendorsWithinDistance($latitude, $longitude, $radius);
            return Vendor::with('address')->whereIn('id', $addressableIds)->paginate();
        });

        return SuccessResponse::send('All Vendors', VendorsSearchResource::collection($vendors), meta: [
            'pagination' => [
                'total' => $vendors->total(),
                'current_page' => $vendors->currentPage(),
                'per_page' => $vendors->perPage(),
                'last_page' => $vendors->lastPage(),
            ]
        ]);
    }

    public function show(Vendor $vendor) {
        return SuccessResponse::send('Vendor details retrieved successfully.', VendorResource::make($vendor));
    }
    public function menu(Vendor $vendor){
        $menu = $vendor->categories()->with('products')->paginate();
        return SuccessResponse::send('Vendor menu retrieved successfully.', [
            'vendor' => VendorResource::make($vendor),
            'menu' => MenuResource::collection($menu)
        ], meta:[
            'pagination' => [
                'total' => $menu->total(),
                'current_page' => $menu->currentPage(),
                'per_page' => $menu->perPage(),
                'last_page' => $menu->lastPage(),
            ]
        ]);
    }
}
