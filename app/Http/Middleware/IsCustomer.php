<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Asumsi 'customer' adalah nama role untuk pembeli
        if (!$request->user() || $request->user()->role->name !== 'customer') {
            return response()->json(['message' => 'Unauthorized. Customer role required.'], 403);
        }

        return $next($request);
    }
}
