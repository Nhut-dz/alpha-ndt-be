<?php

namespace App\Http\Controllers;

use App\Models\PostModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = PostModel::with(['admin', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($posts);
    }

    public function show($id)
    {
        $post = PostModel::with(['admin', 'category'])->findOrFail($id);
        return response()->json($post);
    }

    public function showBySlug($slug)
    {
        $post = PostModel::with(['admin', 'category'])->where('slug', $slug)->firstOrFail();
        return response()->json($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'post_category_id' => 'required|exists:tblposts_category,id',
            'img' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'post_category_id', 'status']);
        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['admin_id'] = $request->user()->id;
        $data['status'] = $request->input('status', 1);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('posts', 'public');
        }

        $post = PostModel::create($data);

        return response()->json($post->load(['admin', 'category']), 201);
    }

    public function update(Request $request, $id)
    {
        $post = PostModel::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'post_category_id' => 'sometimes|required|exists:tblposts_category,id',
            'img' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content', 'post_category_id', 'status']);

        if ($request->has('title') && $request->title !== $post->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        if ($request->hasFile('img')) {
            if ($post->img) {
                \Storage::disk('public')->delete($post->img);
            }
            $data['img'] = $request->file('img')->store('posts', 'public');
        }

        $post->update($data);

        return response()->json($post->load(['admin', 'category']));
    }

    public function destroy($id)
    {
        $post = PostModel::findOrFail($id);
        if ($post->img) {
            \Storage::disk('public')->delete($post->img);
        }
        $post->delete();

        return response()->json(['message' => 'Đã xóa bài viết.']);
    }
}
