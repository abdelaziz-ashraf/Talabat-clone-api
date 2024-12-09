<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Actions\GenerateVerificationCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginDeliveryRequest;
use App\Http\Requests\RegisterDeliveryRequest;
use App\Http\Requests\VerifyDeliveryAccountRequest;
use App\Http\Resources\DeliveryUserResource;
use App\Http\Responses\SuccessResponse;
use App\Models\DeliveryPeople;
use App\Models\VerificationCode;
use App\Notifications\Customer\EmailVerifiedSuccessfullyNotification;
use App\Notifications\Customer\VerificationCodeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register (RegisterDeliveryRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $deliveryMan = DeliveryPeople::create($data);
        $verificationCode = GenerateVerificationCodeAction::handle($deliveryMan);
        $deliveryMan->notify(new VerificationCodeNotification($verificationCode));
        return SuccessResponse::send('Registration Successful, verify your email.', $deliveryMan);
    }

    public function login (LoginDeliveryRequest $request) {
        $data = $request->validated();
        $deliveryUser = DeliveryPeople::where('email', $data['email'])->first();
        if($deliveryUser && !isset($deliveryUser->email_verified_at)) {
            throw ValidationException::withMessages(['please verify your email first.']);
        }
        if(!$deliveryUser || !Hash::check($data['password'], $deliveryUser->password)) {
            throw ValidationException::withMessages(['Credentials does not match']);
        }
        return SuccessResponse::send('Login Successful.', [
            'deliveryUser' => DeliveryUserResource::make($deliveryUser),
            'token' => $deliveryUser->createToken('token')->plainTextToken
        ]);
    }

    public function verifyAccount (VerifyDeliveryAccountRequest $request) {
        $deliveryUser = DeliveryPeople::where('email', $request->email)->first();
        if(!$deliveryUser) {
            throw ValidationException::withMessages(['The provided credentials are incorrect.']);
        }

        $verificationCode = VerificationCode::where('customer_id', $deliveryUser->id)
            ->where('code', $request->code)->first();
        if (! $verificationCode) {
            throw ValidationException::withMessages(['Invalid code.']);
        }

        if ($verificationCode->expires_at < now()) {
            throw ValidationException::withMessages(['Expired code.']);
        }

        $deliveryUser->markEmailAsVerified();
        $deliveryUser->notify(new EmailVerifiedSuccessfullyNotification());
        $verificationCode->delete();
        return SuccessResponse::send('Code verified successfully.');
    }
}
