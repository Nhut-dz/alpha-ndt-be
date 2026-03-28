<?php

namespace App\Services;

use App\Models\ProjectModel;
use Illuminate\Support\Str;

class ProjectService
{
    public function getAll(?int $perPage = null): array
    {
        $query = ProjectModel::with(['admin:id,name'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            $data = $query->paginate($perPage);
            return [
                'success' => true,
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
            ];
        }

        return [
            'success' => true,
            'data' => $query->get(),
        ];
    }

    public function show(int $id): array
    {
        $project = ProjectModel::with(['admin:id,name'])->find($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'Dự án không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $project,
        ];
    }

    public function store(array $data, int $adminId): array
    {
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $data['admin_id'] = $adminId;

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('projects', 'public');
        }

        $project = ProjectModel::create($data);
        $project->load(['admin:id,name']);

        return [
            'success' => true,
            'message' => 'Tạo dự án thành công.',
            'data' => $project,
        ];
    }

    public function update(int $id, array $data): array
    {
        $project = ProjectModel::find($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'Dự án không tồn tại.',
            ];
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        }

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('projects', 'public');
        }

        $project->update($data);
        $project->load(['admin:id,name']);

        return [
            'success' => true,
            'message' => 'Cập nhật dự án thành công.',
            'data' => $project,
        ];
    }

    public function delete(int $id): array
    {
        $project = ProjectModel::find($id);

        if (!$project) {
            return [
                'success' => false,
                'message' => 'Dự án không tồn tại.',
            ];
        }

        $project->delete();

        return [
            'success' => true,
            'message' => 'Xóa dự án thành công.',
        ];
    }

    public function getPublished(?int $perPage = null): array
    {
        $query = ProjectModel::with(['admin:id,name'])
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        if ($perPage) {
            $data = $query->paginate($perPage);
            return [
                'success' => true,
                'data' => $data->items(),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
            ];
        }

        return [
            'success' => true,
            'data' => $query->get(),
        ];
    }

    public function showBySlug(string $slug): array
    {
        $project = ProjectModel::with(['admin:id,name'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$project) {
            return [
                'success' => false,
                'message' => 'Dự án không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $project,
        ];
    }
}
