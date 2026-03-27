<?php

namespace App\Http\Controllers;

use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {}

    public function index(): JsonResponse
    {
        $result = $this->contactService->getAll();
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = $this->contactService->show($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $result = $this->contactService->updateStatus($id, $request->status);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->contactService->delete($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }
}
