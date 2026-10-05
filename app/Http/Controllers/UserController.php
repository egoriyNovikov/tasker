<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->update(
            $request->user(),
            $request->validated(),
        );

        return response()->json($user, 200);
    }
}
