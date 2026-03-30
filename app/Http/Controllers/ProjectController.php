<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectModel::orderBy('sort_order')->orderBy('created_at', 'desc');

        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        return response()->json(ProjectModel::findOrFail($id));
    }

    public function showBySlug($slug)
    {
        return response()->json(ProjectModel::where('slug', $slug)->firstOrFail());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'img' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'client', 'industry',
            'location', 'year', 'duration', 'tag', 'status', 'sort_order',
        ]);

        $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        $data['status'] = $request->input('status', 1);
        $data['highlights'] = $request->input('highlights', []);
        $data['methods'] = $request->input('methods', []);
        $data['standards'] = $request->input('standards', []);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('projects', 'public');
        }

        $project = ProjectModel::create($data);

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = ProjectModel::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'img' => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'title', 'description', 'content', 'client', 'industry',
            'location', 'year', 'duration', 'tag', 'status', 'sort_order',
        ]);

        if ($request->has('title') && $request->title !== $project->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        if ($request->has('highlights')) $data['highlights'] = $request->input('highlights', []);
        if ($request->has('methods')) $data['methods'] = $request->input('methods', []);
        if ($request->has('standards')) $data['standards'] = $request->input('standards', []);

        if ($request->hasFile('img')) {
            if ($project->img) \Storage::disk('public')->delete($project->img);
            $data['img'] = $request->file('img')->store('projects', 'public');
        }

        $project->update($data);

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = ProjectModel::findOrFail($id);
        if ($project->img) \Storage::disk('public')->delete($project->img);
        $project->delete();

        return response()->json(['message' => 'Đã xóa dự án.']);
    }
}
