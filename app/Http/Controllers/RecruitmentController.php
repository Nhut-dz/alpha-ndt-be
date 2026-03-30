<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecruitmentController extends Controller
{
    public function index(Request $request)
    {
        $query = RecruitmentModel::orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        return response()->json(RecruitmentModel::findOrFail($id));
    }

    public function showBySlug($slug)
    {
        return response()->json(RecruitmentModel::where('slug', $slug)->firstOrFail());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'img' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'position', 'department',
            'location', 'employment_type', 'quantity', 'salary_range',
            'requirements', 'benefits', 'deadline', 'contact_email', 'status',
        ]);

        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['status'] = $request->input('status', 1);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('recruitments', 'public');
        }

        $recruitment = RecruitmentModel::create($data);

        return response()->json($recruitment, 201);
    }

    public function update(Request $request, $id)
    {
        $recruitment = RecruitmentModel::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'img' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'position', 'department',
            'location', 'employment_type', 'quantity', 'salary_range',
            'requirements', 'benefits', 'deadline', 'contact_email', 'status',
        ]);

        if ($request->has('title') && $request->title !== $recruitment->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        if ($request->hasFile('img')) {
            if ($recruitment->img) \Storage::disk('public')->delete($recruitment->img);
            $data['img'] = $request->file('img')->store('recruitments', 'public');
        }

        $recruitment->update($data);

        return response()->json($recruitment);
    }

    public function destroy($id)
    {
        $recruitment = RecruitmentModel::findOrFail($id);
        if ($recruitment->img) \Storage::disk('public')->delete($recruitment->img);
        $recruitment->delete();

        return response()->json(['message' => 'Đã xóa tin tuyển dụng.']);
    }
}
