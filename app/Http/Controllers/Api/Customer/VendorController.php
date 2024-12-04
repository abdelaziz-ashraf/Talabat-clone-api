<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $vendors = DB::table('vendors')
            ->join('addresses', function ($join) {
                $join->on('vendors.id', '=', 'addresses.addressable_id')
                    ->where('addresses.addressable_type', Vendor::class);
            })
            ->selectRaw(
                "vendors.*, addresses.address, addresses.city,
            (6371 * acos(cos(radians(?)) * cos(radians(addresses.latitude))
            * cos(radians(addresses.longitude) - radians(?))
            + sin(radians(?)) * sin(radians(addresses.latitude)))) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->having('distance', '<', 50)
            ->orderBy('distance')
            ->when($request->name, function ($query, $name) {
                $query->where('vendors.name', 'like', "%$name%");
            })
            ->paginate();

        return SuccessResponse::send('All Vendors', VendorResource::collection($vendors), meta: [
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
