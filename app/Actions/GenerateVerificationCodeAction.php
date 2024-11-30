<?php

namespace App\Actions;

use App\Models\VerificationCode;
use Illuminate\Support\Str;

class GenerateVerificationCodeAction {
    public static function handle($user) {
        $code = Str::random(6);
        VerificationCode::create([
            'customer_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(50),
        ]);
        return $code;
    }
}
