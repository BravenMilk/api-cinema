<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\CinemaController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ShowtimeController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\SeatTypeController;
use App\Http\Controllers\Api\RoleController;



Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/payment/notification', [\App\Http\Controllers\Api\PaymentController::class, 'notification']);
Route::get('/payment/simulate/{booking_code}', function($booking_code) {
    $bookingService = app(\App\Services\BookingService::class);
    $result = $bookingService->updateStatus($booking_code, 'paid');

    if ($result['success']) {
        $booking = $result['data'];
        return view('payment.success', [
            'booking_code' => $booking->booking_code,
            'total_price' => $booking->total_price
        ]);
    }
    return "Gagal memperbarui status: " . $result['message'];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});

// Lokasi & Bioskop
Route::get('/cities', [LocationController::class, 'cities']);
Route::get('/cities/{cityId}/cinemas', [CinemaController::class, 'byCity']);
Route::get('/cinemas', [CinemaController::class, 'index']);

// Film & Jadwal
Route::get('/movies', [MovieController::class, 'index']);
Route::get('/movies/{id}', [MovieController::class, 'show']);
Route::get('/showtimes', [ShowtimeController::class, 'index']);
Route::get('/showtimes/{id}', [ShowtimeController::class, 'show']);

// Informasi Tambahan
Route::get('/halls', [HallController::class, 'index']);
Route::get('/halls/{id}', [HallController::class, 'show']);
Route::get('/seat-types', [SeatTypeController::class, 'index']);
Route::get('/roles', [RoleController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Harus Login / Membawa Bearer Token)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // === ADMIN ROUTES ===
    Route::middleware(['admin'])->group(function () {
        // RBAC & Core Management
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

        Route::post('/cities', [LocationController::class, 'store']);
        Route::put('/cities/{id}', [LocationController::class, 'update']);
        Route::delete('/cities/{id}', [LocationController::class, 'destroy']);

        Route::post('/cinemas', [CinemaController::class, 'store']);
        Route::put('/cinemas/{id}', [CinemaController::class, 'update']);
        Route::delete('/cinemas/{id}', [CinemaController::class, 'destroy']);

        Route::post('/halls', [HallController::class, 'store']);
        Route::put('/halls/{id}', [HallController::class, 'update']);
        Route::delete('/halls/{id}', [HallController::class, 'destroy']);

        Route::post('/seat-types', [SeatTypeController::class, 'store']);
        Route::put('/seat-types/{id}', [SeatTypeController::class, 'update']);
        Route::delete('/seat-types/{id}', [SeatTypeController::class, 'destroy']);

        Route::get('/seats', [SeatController::class, 'index']);
        Route::post('/seats', [SeatController::class, 'store']);
        Route::put('/seats/{id}', [SeatController::class, 'update']);
        Route::delete('/seats/{id}', [SeatController::class, 'destroy']);

        Route::post('/movies', [MovieController::class, 'store']);
        Route::put('/movies/{id}', [MovieController::class, 'update']);
        Route::delete('/movies/{id}', [MovieController::class, 'destroy']);

        Route::post('/showtimes', [ShowtimeController::class, 'store']);
        Route::put('/showtimes/{id}', [ShowtimeController::class, 'update']);
        Route::delete('/showtimes/{id}', [ShowtimeController::class, 'destroy']);

        // Admin Specific Reports
        Route::get('/bookings/recap', [BookingController::class, 'recap']);
    });

    // === SHARED MANAGEMENT ROUTES (Staff & Admin) ===
    Route::middleware(['staff_or_admin'])->group(function () {
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/tickets/scan', [TicketController::class, 'scan']);
    });

    // === CUSTOMER ROUTES (PEMBELI) ===
    Route::middleware(['customer'])->group(function () {
        Route::get('/seats/layout', [SeatController::class, 'layout']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/my-bookings', [BookingController::class, 'myBookings']);
        Route::post('/bookings/{bookingCode}/cancel', [BookingController::class, 'cancel']);
    });

    Route::middleware(['petugas'])->group(function () {
        // Tambahkan route khusus petugas jika ada
        // Route::post('/tickets/scan', [TicketController::class, 'scan']); // Jika petugas juga bisa scan
    });

});
