<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'petugas' => \App\Http\Middleware\IsPetugas::class,
            'customer' => \App\Http\Middleware\IsCustomer::class,
            'staff' => \App\Http\Middleware\IsStaff::class,
            'staff_or_admin' => \App\Http\Middleware\IsStaffOrAdmin::class,
        ]);

        // Atau jika ingin lebih spesifik:
        $middleware->validateCsrfTokens(except: [
            'api/*', // Kecualikan semua route API dari CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi Anda telah berakhir, silakan login kembali.',
                ], 401);
            }
        });
    })->create();
