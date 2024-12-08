<?php

namespace App\Rules;

use App\Models\Product;
use App\Models\Vendor;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VendorHasProductRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::where('id', $value)->first();
        if($product->category->vendor_id !== request()->vendor_id) {
            $fail('the product does not belong to this vendor');
        }
    }
}
