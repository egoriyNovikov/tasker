<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Models\User;
use App\Models\Task;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository) {}

    public function index(User $user)
    {
        return $this->taskRepository->index($user);
    }

    public function store(User $user, array $data)
    {
        return $this->taskRepository->store($user, $data);
    }

    public function show(Task $task): Task
    {
        return $this->taskRepository->show($task);
    }

    public function update(Task $task, array $data): bool
    {
        return $this->taskRepository->update($task, $data);
    }

    public function destroy(Task $task): bool
    {
        return $this->taskRepository->destroy($task);
    }
}
