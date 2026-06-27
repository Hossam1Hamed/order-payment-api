<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = $this->userRepository->createUser($validated);

        $token = auth('api')->login($user);

        return $this->createdResponse(
            data: ['user' => $user, 'token' => $token],
            message: 'User registered successfully',
        );
    }

    public function login(LoginRequest $request)
    {
        if (!$token = auth('api')->attempt($request->validated())) {
            return $this->unauthorizedResponse('Invalid credentials');
        }

        return $this->successResponse(
            data: ['token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function me()
    {
        return $this->successResponse(
            data: auth('api')->user(),
            message: 'User profile',
        );
    }

    public function logout()
    {
        auth('api')->logout();

        return $this->successResponse(
            message: 'User logged out successfully',
        );
    }
}
