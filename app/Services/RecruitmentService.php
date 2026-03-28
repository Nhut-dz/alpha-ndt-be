<?php

namespace App\Services;

use App\Models\RecruitmentModel;
use Illuminate\Support\Str;

class RecruitmentService
{
    public function getAll(?int $perPage = null): array
    {
        $query = RecruitmentModel::with(['admin:id,name'])
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
        $recruitment = RecruitmentModel::with(['admin:id,name'])->find($id);

        if (!$recruitment) {
            return [
                'success' => false,
                'message' => 'Tin tuyển dụng không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $recruitment,
        ];
    }

    public function store(array $data, int $adminId): array
    {
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $data['admin_id'] = $adminId;

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('recruitments', 'public');
        }

        $recruitment = RecruitmentModel::create($data);
        $recruitment->load(['admin:id,name']);

        return [
            'success' => true,
            'message' => 'Tạo tin tuyển dụng thành công.',
            'data' => $recruitment,
        ];
    }

    public function update(int $id, array $data): array
    {
        $recruitment = RecruitmentModel::find($id);

        if (!$recruitment) {
            return [
                'success' => false,
                'message' => 'Tin tuyển dụng không tồn tại.',
            ];
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        }

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('recruitments', 'public');
        }

        $recruitment->update($data);
        $recruitment->load(['admin:id,name']);

        return [
            'success' => true,
            'message' => 'Cập nhật tin tuyển dụng thành công.',
            'data' => $recruitment,
        ];
    }

    public function delete(int $id): array
    {
        $recruitment = RecruitmentModel::find($id);

        if (!$recruitment) {
            return [
                'success' => false,
                'message' => 'Tin tuyển dụng không tồn tại.',
            ];
        }

        $recruitment->delete();

        return [
            'success' => true,
            'message' => 'Xóa tin tuyển dụng thành công.',
        ];
    }

    public function getPublished(?int $perPage = null): array
    {
        $query = RecruitmentModel::with(['admin:id,name'])
            ->where('status', 1)
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
        $recruitment = RecruitmentModel::with(['admin:id,name'])
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$recruitment) {
            return [
                'success' => false,
                'message' => 'Tin tuyển dụng không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $recruitment,
        ];
    }
}
