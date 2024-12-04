<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\UpdateCustomerRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function show(){
        $customer = auth('customer')->user();
        return SuccessResponse::send('Customer details', CustomerResource::make($customer));
    }

    public function update(UpdateCustomerRequest $request){
        $customer = auth('customer')->user();
        $data = $request->validated();
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        $customer->update($data);
        return SuccessResponse::send('Customer Updated Successfully', CustomerResource::make($customer));
    }

    public function destroy(){
        auth('customer')->user()->delete();
        return SuccessResponse::send('Customer Deleted Successfully');
    }
}
