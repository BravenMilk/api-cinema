<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function notification(Request $request): JsonResponse
    {
        // Validasi input dari "Payment Gateway"
        $request->validate([
            'booking_code' => 'required|string',
            'status' => 'required|string|in:paid,failed,expired'
        ]);

        $bookingCode = $request->input('booking_code');
        $status = $request->input('status');

        $result = $this->bookingService->updateStatus($bookingCode, $status);

        return response()->json($result, $result['code']);
    }
}
