<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(private UserRepository $userRepository) {}

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function login(array $data): User
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
