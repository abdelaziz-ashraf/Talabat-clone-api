<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Vendor;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class AddressController extends Controller
{
    protected $addressService;
    public function __construct(AddressService $addressService){
        $this->addressService = $addressService;
    }

    public function index() {
        $addresses = Cache::remember('customer_addresses'.auth('customer')->id(), now()->addDay(), function() {
            return auth('customer')->user()->addresses()->paginate();
        });

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

    public function store(StoreAddressRequest $request) {
        $user = auth('customer')->user();
        $address = $user->addresses()->create($request->validated());
        Cache::forget('customer_addresses'.auth('customer')->id());
        return SuccessResponse::send('Address added successfully.', AddressResource::make($address));
    }

    public function update(UpdateAddressRequest $request, Address $address) {
        $address = $this->addressService->update($address, $request->validated());
        Cache::forget('customer_addresses'.auth('customer')->id());
        return SuccessResponse::send('AddressService updated successfully.',
            AddressResource::make($address)
        );
    }

    public function destroy(Address $address) {
        $vendor = auth('customer')->user();
        if (!($vendor->id == $address->addressable_id
            && $vendor::class == $address->addressable_type)
        ) {
            throw new UnauthorizedException;
        }
        $this->addressService->destroy($address);
        Cache::forget('customer_addresses'.auth('customer')->id());
        return SuccessResponse::send('Address deleted successfully.');
    }

    public function toggleActive(Address $address) {
        $vendor = auth('customer')->user();
        if (!($vendor->id == $address->addressable_id
            && $vendor::class == $address->addressable_type)
        ) {
            throw new UnauthorizedException;
        }
        $address = $this->addressService->toggleActive($address);
        return SuccessResponse::send('Address Activation updated successfully.', AddressResource::make($address));
    }

}
