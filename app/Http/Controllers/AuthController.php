<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

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

    public function login(LoginRequest $request)
    {
        $user = $this->authService->login(
            $request->validated()
        );

        return response()->json([
            'message' => 'User logged in successfully',
            'id' => $user->id,
        ], 200);
    }

    public function logout(Request $request)
    {

    }
}
