<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request)
    {
        return $this->authService->registerUser($request);
    }

    public function login(LoginUserRequest $request)
    {
        return $this->authService->loginUser($request);
    }

    public function update(UpdateUserRequest $request)
    {
        return $this->authService->updatePasswordUser($request);
    }

    public function request()
    {
        return $this->authService->requestResetPassword();
    }

    public function send(SendEmailRequest $request)
    {
        return $this->authService->sendEmailResetPassword($request);
    }

    public function create($token)
    {
        return $this->authService->createNewPassword($token);
    }

    public function reset(ResetPasswordRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}
