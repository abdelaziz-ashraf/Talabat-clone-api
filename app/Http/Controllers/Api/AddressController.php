<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Address;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    public function customerAddresses() {
        $addresses = auth('customer')->user()->addresses()->paginate();
        return SuccessResponse::send('success', AddressResource::collection($addresses), meta:[
            'pagination' => [
                'total' => $addresses->total(),
                'per_page' => $addresses->perPage(),
                'current_page' => $addresses->currentPage(),
                'last_page' => $addresses->lastPage(),
            ]
        ]);
    }

    public function customerActiveAddresses() {
        $addresses = auth('customer')->user()->addresses()->where('active', true)->paginate();
        return SuccessResponse::send('success', AddressResource::collection($addresses), meta:[
            'pagination' => [
                'total' => $addresses->total(),
                'per_page' => $addresses->perPage(),
                'current_page' => $addresses->currentPage(),
                'last_page' => $addresses->lastPage(),
            ]
        ]);
    }

    public function storeCustomerAddress(StoreAddressRequest $request) {
        $user = auth('customer')->user();
        $address = $user->addresses()->create($request->validated());
        return SuccessResponse::send('Address added successfully.', AddressResource::make($address));
    }

    public function storeVendorAddress(StoreAddressRequest $request) {
        $user = auth('vendor')->user();
        $address = $user->addresses()->create($request->validated());
        return SuccessResponse::send('Address added successfully.', AddressResource::make($address));
    }

    public function update(UpdateAddressRequest $request, Address $address) {
        $address->update($request->validated());
        return SuccessResponse::send('Address updated successfully.', AddressResource::make($address));
    }

    public function destroy(Address $address) {
        $address->delete();
        return SuccessResponse::send('Address deleted successfully.');
    }

    public function toggleActive(Address $address) {
        $address->update([
            'active' => (!$address->active)
        ]);
        return SuccessResponse::send('Address Activation updated successfully.', AddressResource::make($address));
    }

}
