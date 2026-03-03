<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Http\Requests\Api\StoreBookingRequest; // Import Request
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->bookingService->getAllBookings($request->all());
        return response()->json($result, $result['code']);
    }

    public function recap(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->bookingService->getBookingRecap($request->all());
        return response()->json($result, $result['code']);
    }

    public function myBookings(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->bookingService->getMyBookings($request->all());
        return response()->json($result, $result['code']);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        // Validasi otomatis berjalan sebelum baris ini dieksekusi
        $result = $this->bookingService->createBooking($request->validated());
        
        return response()->json($result, $result['code']);
    }

    public function cancel(string $bookingCode): JsonResponse
    {
        $result = $this->bookingService->cancelBooking($bookingCode);
        return response()->json($result, $result['code']);
    }
}
