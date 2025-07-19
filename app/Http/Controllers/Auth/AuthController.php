<?php

namespace App\Http\Controllers\Auth;

use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\ProfileResource;
use App\Mail\Auth\OtpMail;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use HttpResponses;

    public function signup(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:teacher,student'],
        ]);

        $user = User::create([
            'role' => $request->role,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $profile = new ProfileResource($user);
        $data = [
            'profile' => $profile,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];
        $role = $user->role;
        $this->createOtp($user, 'email_verification');

        return $this->success($data, "$role created successfully");
    }

    private function createOtp(User $user, string $type)
    {
        $otp = $user->otps()->create([]);
        Mail::to($user->email)->send(new OtpMail($otp));
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'exists:one_time_passwords,code'],
        ]);

        $otp = $request->user()->otps->where('code', $request->code)->first();

        if (!$otp || $otp->is_expired) {
            return $this->failed(null, StatusCode::BadRequest->value, 'Invalid code or OTP has already expired');
        }

        $request->user()->update([
            'email_verified_at' => now(),
        ]);
        $otp->delete();
        return $this->success(null, 'Email verified successfully');
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $data = new ProfileResource($user);

        return $this->success($data);
    }

    public function signIn(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $isAuthenticated = auth()->attempt($request->only('email', 'password'));

        if (!$isAuthenticated) {
            return $this->failed(null, StatusCode::Unauthorized->value, 'Unauthorized');
        }

        $user = auth()->user();
        $profile = new ProfileResource($user);
        $data = [
            'profile' => $profile,
            'token' => $user->createToken('auth_token')->plainTextToken
        ];

        return $this->success($data, 'Sign in successful');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out successfully');
    }

    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();
        $this->createOtp($user, 'password_reset');

        $this->success(null, 'Password reset link has sent to your email');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'exists:one_time_passwords,code'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);
        $user = User::where('email', $request->email)->first();
        $otp = $user->otps->where('code', $request->code)->first();

        if (!$otp || $otp->is_expired) {
            return $this->failed(null, StatusCode::BadRequest->value, 'Invalid code or OTP has already expired');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $otp->delete();

        return $this->success(null, 'Password reset successfully');
    }
}
