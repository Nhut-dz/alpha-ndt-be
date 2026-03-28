<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UpdateAdminStatusRequest;
use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->adminService->getAll($request->only(['search', 'per_page']));
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->adminService->show($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function profile(Request $request): JsonResponse
    {
        $result = $this->adminService->show($request->user()->id);
        return response()->json($result);
    }

    public function updateStatus(UpdateAdminStatusRequest $request, int $id): JsonResponse
    {
        $result = $this->adminService->updateStatus(
            $id,
            $request->validated()['status'],
            $request->user()->id
        );

        $statusCode = match (true) {
            !$result['success'] && str_contains($result['message'], 'không tồn tại') => 404,
            !$result['success'] => 403,
            default => 200,
        };

        return response()->json($result, $statusCode);
    }
}
