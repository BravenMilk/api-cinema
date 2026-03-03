<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\MovieService;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->movieService->getAllMovies($request->all());
        return response()->json($result, $result['code']);
    }

    public function show($id): JsonResponse
    {
        $result = $this->movieService->getMovieDetail($id);
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->movieService->createMovie($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->movieService->updateMovie($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->movieService->deleteMovie($id);
        return response()->json($result, $result['code']);
    }
}
