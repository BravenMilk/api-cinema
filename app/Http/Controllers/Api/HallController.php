<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HallService;
use Illuminate\Http\JsonResponse;

class HallController extends Controller
{
    protected $hallService;

    public function __construct(HallService $hallService)
    {
        $this->hallService = $hallService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->hallService->getAllHalls($request->all());
        return response()->json($result, $result['code']);
    }

    /**
     * Mengambil detail studio berdasarkan ID
     */
    public function show($id): JsonResponse
    {
        $result = $this->hallService->getHallDetails($id);
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->hallService->createHall($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->hallService->updateHall($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->hallService->deleteHall($id);
        return response()->json($result, $result['code']);
    }
}
