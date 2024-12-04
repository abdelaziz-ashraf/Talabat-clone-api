<?php

namespace App\Http\Controllers\Api\Customer;

use App\Actions\GenerateVerificationCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\CustomerLoginRequest;
use App\Http\Requests\Customer\Auth\CustomerRegisterRequest;
use App\Http\Requests\Customer\Auth\CustomerVerifyCodeRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Customer;
use App\Models\VerificationCode;
use App\Notifications\Customer\EmailVerifiedSuccessfullyNotification;
use App\Notifications\Customer\VerificationCodeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(CustomerRegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $customer = Customer::create($data);

        $verificationCode = GenerateVerificationCodeAction::handle($customer);
        $customer->notify(new VerificationCodeNotification($verificationCode));

        return SuccessResponse::send('Registered successfully.', CustomerResource::make($customer));
    }


    public function login(CustomerLoginRequest $request)
    {
        $data = $request->validated();
        $customer = Customer::where('email', $data['email'])->first();
        if ($customer && !isset($customer->email_verified_at)) {
            throw ValidationException::withMessages(['please verify your email first.']);
        }
        if(!$customer || !Hash::check($data['password'], $customer->password)) {
            throw ValidationException::withMessages(['Credentials does not match']);
        }

        return SuccessResponse::send('Login successfully.', [
            'customer' => CustomerResource::make($customer),
            'token' => $customer->createToken('token')->plainTextToken
        ]);
    }

    public function verifyCode(CustomerVerifyCodeRequest $request)
    {
        $customer = Customer::where('email', $request->email)->first();
        if (! $customer) {
            throw ValidationException::withMessages(['The provided credentials are incorrect.']);
        }

        $verificationCode = VerificationCode::where('customer_id', $customer->id)
            ->where('code', $request->code)->first();
        if (! $verificationCode) {
            throw ValidationException::withMessages(['Invalid code.']);
        }

        if ($verificationCode->expires_at < now()) {
            throw ValidationException::withMessages(['Expired code.']);
        }

        $customer->markEmailAsVerified();
        $customer->notify(new EmailVerifiedSuccessfullyNotification());
        $verificationCode->delete();

        return SuccessResponse::send('Code verified successfully.');
    }

}
