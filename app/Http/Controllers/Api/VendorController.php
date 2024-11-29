<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{

    public function index() {
        $vendors =  Vendor::paginate();
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

    public function update(UpdateVendorRequest $request, Vendor $vendor) {
        $data = $request->validated();
        if(isset($data->password)) {
            $data['password'] = Hash::make($data->password);
        }
        // todo: update image
        $vendor->update($request->validated());
        return SuccessResponse::send('Vendor details updated successfully.', VendorResource::make($vendor));
    }

    public function destroy(Vendor $vendor) {
        $vendor->delete();
        // todo: delete image
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

