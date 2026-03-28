<?php

namespace App\Services;

use App\Models\AdminModel;

class AdminService
{
    public function getAll(array $params): array
    {
        $query = AdminModel::with('role');

        // Search by name or email
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $params['per_page'] ?? 15;
        $admins = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Transform data to include role name
        $admins->getCollection()->transform(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role->name ?? null,
                'status' => $admin->status,
                'created_at' => $admin->created_at,
            ];
        });

        return [
            'success' => true,
            'data' => $admins,
        ];
    }

    public function show(int $id): array
    {
        $admin = AdminModel::with('role')->find($id);

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Tài khoản không tồn tại.',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role->name ?? null,
                'role_id' => $admin->role_id,
                'status' => $admin->status,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ],
        ];
    }

    public function updateStatus(int $id, int $status, int $currentAdminId): array
    {
        $admin = AdminModel::find($id);

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Tài khoản không tồn tại.',
            ];
        }

        // Cannot block yourself
        if ($admin->id === $currentAdminId) {
            return [
                'success' => false,
                'message' => 'Không thể thay đổi trạng thái tài khoản của chính mình.',
            ];
        }

        $admin->status = $status;
        $admin->save();

        return [
            'success' => true,
            'message' => $status === 1 ? 'Mở khóa tài khoản thành công.' : 'Khóa tài khoản thành công.',
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status,
            ],
        ];
    }
}
