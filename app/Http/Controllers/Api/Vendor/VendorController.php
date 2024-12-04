<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Profile\UpdateVendorRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function show() {
        $vendor = auth('vendor')->user();
        return SuccessResponse::send('vendor', new VendorResource($vendor));
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
        $vendor->update($data);
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

    public function menu(){
        $menu = auth('vendor')->user()->categories()->with('products')->paginate();
        return SuccessResponse::send('Vendor menu retrieved successfully.',  MenuResource::collection($menu), meta:[
            'pagination' => [
                'total' => $menu->total(),
                'current_page' => $menu->currentPage(),
                'per_page' => $menu->perPage(),
                'last_page' => $menu->lastPage(),
            ]
        ]);
    }
}

