<?php

namespace App\Http\Controllers;

use App\Models\AdminModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminModel::with('role')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $admins = $query->paginate($perPage);

        return response()->json($admins);
    }

    public function show($id)
    {
        return response()->json(AdminModel::with('role')->findOrFail($id));
    }

    public function updateStatus(Request $request, $id)
    {
        $admin = AdminModel::findOrFail($id);
        $admin->update(['status' => $request->status]);
        return response()->json($admin->load('role'));
    }
}
