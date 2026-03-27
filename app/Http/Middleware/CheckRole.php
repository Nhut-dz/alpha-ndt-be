<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:Super Admin,Post_Admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $admin = $request->user();

        if (!$admin || !$admin->role) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập.',
            ], 403);
        }

        // Super Admin always has access
        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        if (!in_array($admin->role->name, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        return $next($request);
    }
}
