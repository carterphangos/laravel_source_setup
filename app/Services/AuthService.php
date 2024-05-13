<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendPasswordResetEmail;

class AuthService
{
    public function registerUser(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePasswordUser(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ], 200);
    }

    public function requestResetPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendEmailResetPassword(Request $request)
    {
        try {
            DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
 
            $token = Str::random(60);
            $expiresAt = Carbon::now('Asia/Ho_Chi_Minh')->addMinutes(1);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => $expiresAt,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            ]);

            SendPasswordResetEmail::dispatch($token, $expiresAt, $request->email);

            return response()->json([
                'status' => true,
                'message' => 'We have e-mailed your password reset link!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while sending the password reset email. Please try again later.',
                'log' => $e->getMessage(),
            ], 500);
        }
    }

    public function createNewPassword($token)
    {
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)
            ->where('expires_at', '>', Carbon::now('Asia/Ho_Chi_Minh'))->first();
        if ($passwordReset) {
            return view('auth.reset-password', ['token' => $token]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'This password reset token is invalid or has expired.',
            ], 404);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $passwordReset = DB::table('password_reset_tokens')->where('token', $request->token)
                ->where('expires_at', '>', Carbon::now('Asia/Ho_Chi_Minh'))->first();

            if ($passwordReset) {
                $user = User::where('email', $passwordReset->email)->first();
                $user->password = Hash::make($request->password);
                $user->save();

                DB::table('password_reset_tokens')->where('token', $request->token)->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Your password has been reset successfully.',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'This password reset token is invalid or has expired.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while resetting your password. Please try again later.',
                'log' => $e->getMessage(),
            ], 500);
        }
    }
}
