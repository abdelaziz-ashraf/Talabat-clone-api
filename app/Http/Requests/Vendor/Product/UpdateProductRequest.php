<?php

namespace App\Http\Requests\Vendor\Product;

use App\Rules\Vendor\CheckVendorOwnsCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(auth('vendor')->id() !== request()->route('product')->category->vendor_id) {
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
            'name' => 'string|max:255',
            'price' => 'numeric|min:1|max:10000',
            'description' => 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => ['nullable', 'exists:categories,id', new CheckVendorOwnsCategory]
        ];
    }
}
