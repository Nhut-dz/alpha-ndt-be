<?php

namespace App\Services;

use App\Models\PostModel;
use Illuminate\Support\Str;

class PostService
{
    public function getAll(): array
    {
        $posts = PostModel::with(['admin:id,name', 'category:id,name,slug'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'success' => true,
            'data' => $posts,
        ];
    }

    public function show(int $id): array
    {
        $post = PostModel::with(['admin:id,name', 'category:id,name,slug'])->find($id);

        if (!$post) {
            return [
                'success' => false,
                'message' => 'Bài viết không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => $post,
        ];
    }

    public function store(array $data, int $adminId): array
    {
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        $data['admin_id'] = $adminId;

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('posts', 'public');
        }

        $post = PostModel::create($data);
        $post->load(['admin:id,name', 'category:id,name,slug']);

        return [
            'success' => true,
            'message' => 'Tạo bài viết thành công.',
            'data' => $post,
        ];
    }

    public function update(int $id, array $data): array
    {
        $post = PostModel::find($id);

        if (!$post) {
            return [
                'success' => false,
                'message' => 'Bài viết không tồn tại.',
            ];
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        }

        if (isset($data['img']) && $data['img'] instanceof \Illuminate\Http\UploadedFile) {
            $data['img'] = $data['img']->store('posts', 'public');
        }

        $post->update($data);
        $post->load(['admin:id,name', 'category:id,name,slug']);

        return [
            'success' => true,
            'message' => 'Cập nhật bài viết thành công.',
            'data' => $post,
        ];
    }

    public function delete(int $id): array
    {
        $post = PostModel::find($id);

        if (!$post) {
            return [
                'success' => false,
                'message' => 'Bài viết không tồn tại.',
            ];
        }

        $post->delete();

        return [
            'success' => true,
            'message' => 'Xóa bài viết thành công.',
        ];
    }
}
