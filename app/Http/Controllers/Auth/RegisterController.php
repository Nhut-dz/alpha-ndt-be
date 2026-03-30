<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tbladmins,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:tblroles,id',
        ]);

        $admin = AdminModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id,
        ]);

        $token = $admin->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $admin->load('role'),
        ], 201);
    }
}
