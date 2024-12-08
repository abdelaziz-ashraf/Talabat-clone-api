<?php

namespace App\Http\Requests\Customer\Cart;

use App\Rules\VendorHasProductRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'vendor_id' =>'required|exists:vendors,id',
            'product_id' => ['required', 'exists:products,id', new VendorHasProductRule()],
            'quantity' => 'required|integer'
        ];
    }
}
