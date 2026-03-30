<?php

namespace App\Http\Controllers;

use App\Models\PostCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        return response()->json(PostCategoryModel::orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tblposts_category,name',
        ]);

        $category = PostCategoryModel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->input('status', 1),
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = PostCategoryModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:tblposts_category,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->input('status', $category->status),
        ]);

        return response()->json($category);
    }

    public function destroy($id)
    {
        PostCategoryModel::findOrFail($id)->delete();
        return response()->json(['message' => 'Đã xóa danh mục.']);
    }
}
