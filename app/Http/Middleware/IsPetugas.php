<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPetugas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Asumsi 'petugas' adalah nama role di database
        if (!$request->user() || $request->user()->role->name !== 'petugas') {
            return response()->json(['message' => 'Unauthorized. Petugas role required.'], 403);
        }

        return $next($request);
    }
}
