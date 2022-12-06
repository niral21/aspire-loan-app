<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends APIController
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authRepository->register($request);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authRepository->login($request);
    }

    public function logout(): JsonResponse
    {
        return $this->authRepository->logout();
    }
}
