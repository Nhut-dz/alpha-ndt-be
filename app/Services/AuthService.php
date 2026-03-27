<?php

namespace App\Services;

use App\Models\AdminModel;
use App\Models\OtpCodeModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Login admin and create Sanctum token
     */
    public function login(string $email, string $password, bool $remember = false): array
    {
        $admin = AdminModel::where('email', $email)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return [
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng.',
            ];
        }

        $admin->load('role');

        $tokenName = $remember ? 'auth_token_remember' : 'auth_token';
        $token = $admin->createToken($tokenName)->plainTextToken;

        return [
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role->name,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }

    /**
     * Register new admin (only Super Admin can do this)
     */
    public function register(array $data): array
    {
        $admin = AdminModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
        ]);

        $admin->load('role');

        return [
            'success' => true,
            'message' => 'Tạo tài khoản thành công.',
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role->name,
                ],
            ],
        ];
    }

    /**
     * Logout - revoke current token
     */
    public function logout(AdminModel $admin): array
    {
        $admin->currentAccessToken()->delete();

        return [
            'success' => true,
            'message' => 'Đăng xuất thành công.',
        ];
    }

    /**
     * Send OTP code for password reset (no expiry)
     */
    public function forgotPassword(string $email): array
    {
        $admin = AdminModel::where('email', $email)->first();

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.',
            ];
        }

        // Invalidate old OTPs
        OtpCodeModel::where('email', $email)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCodeModel::create([
            'email' => $email,
            'otp' => $otp,
            'is_used' => false,
        ]);

        // TODO: Send OTP via email (Mail::to($email)->send(...))
        // For now, return OTP in response (dev mode)

        return [
            'success' => true,
            'message' => 'Mã OTP đã được gửi đến email của bạn.',
            'data' => [
                'otp' => $otp, // Remove in production
            ],
        ];
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $email, string $otp): array
    {
        $otpRecord = OtpCodeModel::where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return [
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã được sử dụng.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Xác thực OTP thành công.',
        ];
    }

    /**
     * Reset password with OTP
     */
    public function resetPassword(string $email, string $otp, string $newPassword): array
    {
        $otpRecord = OtpCodeModel::where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return [
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã được sử dụng.',
            ];
        }

        $admin = AdminModel::where('email', $email)->first();

        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Email không tồn tại trong hệ thống.',
            ];
        }

        $admin->update(['password' => $newPassword]);

        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);

        // Revoke all tokens
        $admin->tokens()->delete();

        return [
            'success' => true,
            'message' => 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.',
        ];
    }

    /**
     * Change password (authenticated)
     */
    public function changePassword(AdminModel $admin, string $currentPassword, string $newPassword): array
    {
        if (!Hash::check($currentPassword, $admin->password)) {
            return [
                'success' => false,
                'message' => 'Mật khẩu hiện tại không đúng.',
            ];
        }

        $admin->update(['password' => $newPassword]);

        return [
            'success' => true,
            'message' => 'Đổi mật khẩu thành công.',
        ];
    }

    /**
     * Get current admin profile
     */
    public function profile(AdminModel $admin): array
    {
        $admin->load('role');

        return [
            'success' => true,
            'data' => [
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role->name,
                ],
            ],
        ];
    }
}
