<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaffOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = ['admin', 'manager', 'superadmin', 'staff', 'petugas', 'staf'];
        
        if (!$request->user() || !in_array(strtolower($request->user()->role->name), $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Staff or Admin role required.'
            ], 403);
        }

        return $next($request);
    }
}
