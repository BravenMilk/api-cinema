<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->roleService->getRoles($request->all());
        return response()->json($result, $result['code']);
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $result = $this->roleService->createRole($request->all());
        return response()->json($result, $result['code']);
    }

    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $result = $this->roleService->updateRole($id, $request->all());
        return response()->json($result, $result['code']);
    }

    public function destroy($id): JsonResponse
    {
        $result = $this->roleService->deleteRole($id);
        return response()->json($result, $result['code']);
    }
}
