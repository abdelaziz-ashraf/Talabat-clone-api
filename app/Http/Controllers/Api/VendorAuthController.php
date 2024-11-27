<?php

namespace App\Http\Controllers\Api;

use App\Actions\LocalFileUploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\VendorLoginRequest;
use App\Http\Requests\VendorRegisterRequest;
use App\Http\Resources\VendorResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class VendorAuthController extends Controller
{
    public function register(VendorRegisterRequest $request, LocalFileUploader $localFileUploader){
        $address = new AddressRequest($request->post('address'));
        $data = $request->validated();

        $data['image'] = $request->hasFile('image')
            ? $localFileUploader->upload($request->file('image'), 'vendors_images')
            : null;

        $vendor = Vendor::create([
            'name' => $data['name'],
            'image' => $data['image'],
        ]);
        $vendor->addresses()->create([
            'address' => $address['address'],
            'city' => $address['city'],
            'latitude' => $address['latitude'],
            'longitude' => $address['longitude'],
        ]);

        return SuccessResponse::send('Wait approving from Admin .. ', VendorResource::make($vendor));
    }

    public function login(VendorLoginRequest $request) {

        $vendor = Vendor::where('code', $request->post('code'))->first();

        if(!$vendor || !Hash::check($request->post('password'), $vendor->password)) {
            throw ValidationException::withMessages(['Credentials not matched']);
        }

        return SuccessResponse::send('Login Successful', [
            'vendor' => VendorResource::make($vendor),
            'token' => $vendor->createToken('VendorToken')->plainTextToken,
        ]);
    }

    public function setPassword(SetPasswordRequest $request, Vendor $vendor){

        if(!is_null($vendor->password)) {
            throw ValidationException::withMessages(['You set your password before .. change it from your profile if you want.']);
        }

        $vendor->update(['password' => bcrypt($request->post('password'))]);
        return SuccessResponse::send('You can login now');
    }
}
