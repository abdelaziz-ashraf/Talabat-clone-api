<?php

namespace App\Http\Controllers\Api;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Address;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            ->having('distance', '<', 20)
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

    public function update(UpdateVendorRequest $request, LocalFileUploader $localFileUploader) {
        $vendor = auth('vendor')->user();
        $data = $request->validated();
        if(isset($data['password'])) {
            $data['password'] = Hash::make($data->password);
        }
        if($request->hasFile('image')){
            $data['image'] = $localFileUploader->upload($request->file('image'), 'vendors_images', $vendor->image ?? null);
        }
        $vendor->update($request->validated());
        return SuccessResponse::send('Vendor details updated successfully.', VendorResource::make($vendor));
    }

    public function destroy(LocalFileUploader $localFileUploader) {
        $vendor = auth('vendor')->user();
        $image = isset($vendor->image) ? $vendor->image : null;
        $vendor->delete();
        if($image !== null) {
            $localFileUploader->deleteFile('vendors_images/'.$image);
        }
        return SuccessResponse::send('Vendor deleted successfully.');
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

