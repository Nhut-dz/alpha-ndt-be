<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page') ? (int) $request->query('per_page') : null;
        $result = $this->projectService->getAll($perPage);
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->projectService->show($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $result = $this->projectService->store(
            $request->validated(),
            $request->user()->id
        );
        return response()->json($result, 201);
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $result = $this->projectService->update($id, $request->validated());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->projectService->delete($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    // Public endpoints (no auth)
    public function publicIndex(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page') ? (int) $request->query('per_page') : null;
        $result = $this->projectService->getPublished($perPage);
        return response()->json($result);
    }

    public function publicShow(string $slug): JsonResponse
    {
        $result = $this->projectService->showBySlug($slug);
        return response()->json($result, $result['success'] ? 200 : 404);
    }
}
