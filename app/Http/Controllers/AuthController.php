<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request)
    {
        $user = $this->authService->registerUser($request);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken('Access Token', ['*'], now()->addHours(2))->plainTextToken,
        ], Response::HTTP_OK);
    }

    public function login(LoginUserRequest $request)
    {
        $result = $this->authService->loginUser($request);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'access_token' => $result['access_token'] ? $result['access_token'] : $result->createToken('Access Token', ['*'], now()->addHours(2))->plainTextToken,
            'refresh_token' => $result['refresh_token'] ? $result['refresh_token'] : null,
        ], Response::HTTP_OK);
    }

    public function refresh(Request $request)
    {
        $result = $this->authService->refreshToken($request);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
        ], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request)
    {
        $this->authService->updatePasswordUser($request);

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ], Response::HTTP_OK);
    }

    public function request()
    {
        return $this->authService->requestResetPassword();
    }

    public function send(SendEmailRequest $request)
    {
        $this->authService->sendEmailResetPassword($request);

        return response()->json([
            'status' => true,
            'message' => 'We have e-mailed your password reset link!',
        ], Response::HTTP_OK);
    }

    public function create($token)
    {
        if ($this->authService->createNewPassword($token)) {
            return view('auth.reset-password', ['token' => $token]);
        }

        return response()->json([
            'status' => false,
            'message' => 'This password reset token is invalid or has expired.',
        ], Response::HTTP_NOT_FOUND);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request);

        if ($result) {
            return response()->json([
                'status' => true,
                'message' => 'Your password has been reset successfully.',
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => false,
            'message' => 'This password reset token is invalid or has expired.',
        ], Response::HTTP_NOT_FOUND);
    }
}
