<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShowtimeService;
use Illuminate\Http\JsonResponse;

class ShowtimeController extends Controller
{
    protected $showtimeService;

    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->showtimeService->getAllShowtimes($request->all());
        return response()->json($result, $result['code']);
    }

    public function show($id): JsonResponse
    {
        if (!\App\Models\Showtime::where('id', $id)->exists()) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $result = $this->showtimeService->getShowtimeDetails($id);
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->showtimeService->createShowtime($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->showtimeService->updateShowtime($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->showtimeService->deleteShowtime($id);
        return response()->json($result, $result['code']);
    }
}
