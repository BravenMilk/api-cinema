<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeatTypeService;
use Illuminate\Http\JsonResponse;

class SeatTypeController extends Controller
{
    protected $seatTypeService;

    public function __construct(SeatTypeService $seatTypeService)
    {
        $this->seatTypeService = $seatTypeService;
    }

    /**
     * Menampilkan semua jenis kursi yang tersedia
     */
    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->seatTypeService->getAllTypes($request->all());
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->seatTypeService->createType($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->seatTypeService->updateType($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->seatTypeService->deleteType($id);
        return response()->json($result, $result['code']);
    }
}
