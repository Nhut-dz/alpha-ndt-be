<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->postService->getAll();
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->postService->show($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $result = $this->postService->store(
            $request->validated(),
            $request->user()->id
        );
        return response()->json($result, 201);
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        $result = $this->postService->update($id, $request->validated());
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->postService->delete($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }
}
