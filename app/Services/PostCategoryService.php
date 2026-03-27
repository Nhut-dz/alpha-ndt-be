<?php

namespace App\Services;

use App\Models\PostCategoryModel;
use Illuminate\Support\Str;

class PostCategoryService
{
    public function getAll(): array
    {
        $categories = PostCategoryModel::orderBy('created_at', 'desc')->get();

        return [
            'success' => true,
            'data' => $categories,
        ];
    }

    public function store(array $data): array
    {
        $data['slug'] = Str::slug($data['name']);

        $category = PostCategoryModel::create($data);

        return [
            'success' => true,
            'message' => 'Tạo danh mục thành công.',
            'data' => $category,
        ];
    }

    public function update(int $id, array $data): array
    {
        $category = PostCategoryModel::find($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Danh mục không tồn tại.',
            ];
        }

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return [
            'success' => true,
            'message' => 'Cập nhật danh mục thành công.',
            'data' => $category,
        ];
    }

    public function delete(int $id): array
    {
        $category = PostCategoryModel::find($id);

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Danh mục không tồn tại.',
            ];
        }

        if ($category->posts()->count() > 0) {
            return [
                'success' => false,
                'message' => 'Không thể xóa danh mục đang có bài viết.',
            ];
        }

        $category->delete();

        return [
            'success' => true,
            'message' => 'Xóa danh mục thành công.',
        ];
    }
}
