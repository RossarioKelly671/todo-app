<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    public function register(RegisterRequest $request): RegisterResource
    {
        return RegisterResource::make(
            $this->authService->register($request->validated())
        );
    }

    public function login(LoginRequest $request): LoginResource
    {
        return LoginResource::make(
            $this->authService->login($request->validated())
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request): void
    {
        $this->authService->forgotPassword($request->validated());
    }

    public function resetPassword(ResetPasswordRequest $request): void
    {
        $this->authService->resetPassword($request->validated());
    }

}
