<?php

namespace App\Http\Requests\Vendor\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'password' => 'string|min:8',
        ];
    }
}
