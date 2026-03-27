<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCategory\StorePostCategoryRequest;
use App\Http\Requests\PostCategory\UpdatePostCategoryRequest;
use App\Services\PostCategoryService;
use Illuminate\Http\JsonResponse;

class PostCategoryController extends Controller
{
    public function __construct(
        protected PostCategoryService $postCategoryService
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->postCategoryService->getAll();
        return response()->json($result);
    }

    public function store(StorePostCategoryRequest $request): JsonResponse
    {
        $result = $this->postCategoryService->store($request->validated());
        return response()->json($result, 201);
    }

    public function update(UpdatePostCategoryRequest $request, int $id): JsonResponse
    {
        $result = $this->postCategoryService->update($id, $request->validated());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->postCategoryService->delete($id);
        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
