<?php

namespace App\Http\Controllers;

use App\Http\Requests\Recruitment\StoreRecruitmentRequest;
use App\Http\Requests\Recruitment\UpdateRecruitmentRequest;
use App\Services\RecruitmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function __construct(
        protected RecruitmentService $recruitmentService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page') ? (int) $request->query('per_page') : null;
        $result = $this->recruitmentService->getAll($perPage);
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->recruitmentService->show($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(StoreRecruitmentRequest $request): JsonResponse
    {
        $result = $this->recruitmentService->store(
            $request->validated(),
            $request->user()->id
        );
        return response()->json($result, 201);
    }

    public function update(UpdateRecruitmentRequest $request, int $id): JsonResponse
    {
        $result = $this->recruitmentService->update($id, $request->validated());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->recruitmentService->delete($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    // Public endpoints (no auth)
    public function publicIndex(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page') ? (int) $request->query('per_page') : null;
        $result = $this->recruitmentService->getPublished($perPage);
        return response()->json($result);
    }

    public function publicShow(string $slug): JsonResponse
    {
        $result = $this->recruitmentService->showBySlug($slug);
        return response()->json($result, $result['success'] ? 200 : 404);
    }
}
