<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * @param  array{name?: string, email?: string, password?: string, telegram_login?: string|null}  $data
     */
    public function update(User $user, array $data): User
    {
        return $this->userRepository->update($user, $data);
    }
}
