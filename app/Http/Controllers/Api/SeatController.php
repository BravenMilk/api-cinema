<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeatService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SeatLayoutRequest;
use App\Models\Showtime;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    protected $seatService;

    public function __construct(SeatService $seatService)
    {
        $this->seatService = $seatService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->seatService->getAllSeats($request->all());
        return response()->json($result, $result['code']);
    }

    public function layout(SeatLayoutRequest $request): JsonResponse
    {
        // Jika hall_id tidak dikirim, ambil otomatis dari showtime
        $hallId = $request->hall_id;
        if (!$hallId) {
            $showtime = Showtime::find($request->showtime_id);
            if (!$showtime) {
                return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
            }
            $hallId = $showtime->hall_id;
        }

        $result = $this->seatService->getSeatLayout($hallId, $request->showtime_id);
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->seatService->createSeat($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->seatService->updateSeat($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->seatService->deleteSeat($id);
        return response()->json($result, $result['code']);
    }
}

