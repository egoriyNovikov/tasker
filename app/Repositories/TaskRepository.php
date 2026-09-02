<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Task;

class TaskRepository
{
    public function index(User $user )
    {
        return $user->tasks;
    }

    public function store(User $user, array $data)
    {
        return $user->tasks()->create($data);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->refresh();
    }

    public function destroy(Task $task)
    {
        return $task->delete();
    }
}
