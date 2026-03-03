<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function cities(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->locationService->getAllCities($request->all());
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->locationService->createCity($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->locationService->updateCity($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->locationService->deleteCity($id);
        return response()->json($result, $result['code']);
    }
}
