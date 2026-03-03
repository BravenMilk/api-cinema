<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CinemaService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\GetByCityRequest;

class CinemaController extends Controller
{
    protected $cinemaService;

    public function __construct(CinemaService $cinemaService)
    {
        $this->cinemaService = $cinemaService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->cinemaService->getAllCinemas($request->all());
        return response()->json($result, $result['code']);
    }

    public function byCity(GetByCityRequest $request, $cityId): JsonResponse
    {
        // Kita validasi cityId yang datang dari parameter
        $result = $this->cinemaService->getCinemasByCity($cityId);
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->cinemaService->createCinema($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->cinemaService->updateCinema($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->cinemaService->deleteCinema($id);
        return response()->json($result, $result['code']);
    }
}
