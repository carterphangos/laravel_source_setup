<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function registerUser(RegisterUserRequest $request)
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

    public function loginUser(LoginUserRequest $request)
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

    public function updatePasswordUser(UpdateUserRequest $request)
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

    public function sendEmailResetPassword(SendEmailRequest $request)
    {
        try {
            $token = Str::random(60);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            Mail::to($request->email)->send(new ResetPassword($token));

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
        $passwordReset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($passwordReset) {
            return view('auth.reset-password', ['token' => $token]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
            ], 404);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $passwordReset = DB::table('password_reset_tokens')->where('token', $request->token)->first();

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
                    'message' => 'Invalid token or email address.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while resetting your password. Please try again later.',
                'log'=> $e->getMessage(),
            ], 500);
        }
    }
}
