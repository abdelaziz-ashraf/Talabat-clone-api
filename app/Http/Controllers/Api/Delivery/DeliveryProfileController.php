<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDeliveryProfileRequest;
use App\Http\Resources\DeliveryUserResource;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Hash;

class DeliveryProfileController extends Controller
{
    public function show() {
        $deliveryUser = auth('delivery')->user();
        return SuccessResponse::send('Your Profile', DeliveryUserResource::make($deliveryUser));
    }

    public function update(UpdateDeliveryProfileRequest $request) {
        $data = $request->validated();
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        $deliveryUser = auth('delivery')->user();
        $deliveryUser->update($data);
        return SuccessResponse::send('Profile Updated', DeliveryUserResource::make($deliveryUser));
    }

    public function delete() {
        $deliveryUser = auth('delivery')->user();
        $deliveryUser->delete();
        return SuccessResponse::send('Profile Deleted Successfully');
    }

}
