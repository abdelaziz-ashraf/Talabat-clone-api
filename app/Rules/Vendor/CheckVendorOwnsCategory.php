<?php

namespace App\Rules\Vendor;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckVendorOwnsCategory implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = auth('vendor')->user()->categories()->find($value);
        if (!$category) {
            $fail('Category not found.');
        }
    }
}
