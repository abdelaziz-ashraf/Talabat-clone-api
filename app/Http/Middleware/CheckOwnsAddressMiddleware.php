<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnsAddressMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $address = $request->route('address');

        $user = $user->user();
        if (
            in_array($user::class, [Vendor::class, Customer::class])
            &&
            ($user->id == $address->addressable_id)
            &&
            ($user::class == $address->addressable_type)
        ) {
            return $next($request);
        }
        throw ValidationException::withMessages(['Unauthorized Access.']);
    }
}
