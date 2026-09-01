<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register(
            $request->validated()
        );

        return response()->json([
            'message' => 'User registered successfully',
            'id' => $user->id,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login(
            $request->validated()
        );

        return response()->json([
            'message' => 'User logged in successfully',
            'id' => $user->id,
            'access_token' => $user->createToken('api-token')->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request) {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
