<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = AdminModel::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng.'], 401);
        }

        if (isset($admin->status) && $admin->status == 0) {
            return response()->json(['message' => 'Tài khoản đã bị khóa.'], 403);
        }

        $token = $admin->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $admin->load('role'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Đã đăng xuất.']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mật khẩu hiện tại không đúng.'], 422);
        }

        $user->update(['password' => $request->password]);
        return response()->json(['message' => 'Đổi mật khẩu thành công.']);
    }
}
