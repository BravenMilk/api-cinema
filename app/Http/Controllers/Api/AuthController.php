<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        // Menggunakan $request->validated() agar hanya data yang divalidasi yang diproses
        $result = $this->authService->login($request->validated());

        return response()->json($result, $result['code']);
    }

    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService->logout($request->user());

        return response()->json($result, $result['code']);
    }

    public function register(\App\Http\Requests\Api\RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, $result['code']);
    }

    public function updateProfile(\App\Http\Requests\Api\UpdateProfileRequest $request): JsonResponse
    {
        $result = $this->authService->updateProfile($request->user(), $request->validated());

        return response()->json($result, $result['code']);
    }
}
