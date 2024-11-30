<?php

namespace App\Http\Middleware;

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
        if (auth('vendor')->id() !== $address->addressable_id) {
            throw ValidationException::withMessages(['Unauthorized Access.']);
        }

        return $next($request);
    }
}
