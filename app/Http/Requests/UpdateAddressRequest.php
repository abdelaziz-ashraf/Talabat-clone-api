<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $address = $this->route('address');
        $user = auth()->user();
        if (!(
                in_array($user::class, [Vendor::class, Customer::class])
                &&
                ($user->id == $address->addressable_id)
                &&
                ($user::class == $address->addressable_type)
            )
        ) {
            return false;
        }
        return true;
    }

    protected function failedAuthorization() {
        throw new UnauthorizedException;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address' => 'string|max:255',
            'city' => 'string|max:100',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
        ];
    }
}
