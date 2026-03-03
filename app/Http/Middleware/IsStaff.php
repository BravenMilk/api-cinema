<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Menggunakan 'staff' sebagai nama role (user menyebut 'stag' tapi kemungkinan typo 'staff' atau 'stage', saya asumsi 'staff')
        if (!$request->user() || !in_array($request->user()->role->name, ['staff', 'staf'])) {
            return response()->json(['message' => 'Unauthorized. Staff role required.'], 403);
        }

        return $next($request);
    }
}
