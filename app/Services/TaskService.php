<?php

namespace App\Services;

use App\Client\OllamaClient;
use App\Repositories\TaskRepository;
use App\Models\User;
use App\Models\Task;
use RuntimeException;

class TaskService
{
    public function __construct(private TaskRepository $taskRepository, private OllamaClient $ollamaClient) {}

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

    public function update(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    public function destroy(Task $task): bool
    {
        return $this->taskRepository->destroy($task);
    }

    public function interpret(User $user, string $text, ?float $confidence)
    {
        $system_message = file_get_contents(
            base_path('resources/prompts/task_interpreter.txt')
        );

        $system_message .= "\n\nТекущая дата и время: " . now()->toIso8601String();
        $answer = json_decode($this->ollamaClient->interpret($text, $confidence, $system_message), true, 512, JSON_THROW_ON_ERROR);

        switch ($answer['action'] ?? null) {
            case 'create_task':
                return [
                    'action' => $answer['action'],
                    'task' => $this->store($user, $answer['data'])
                ];
            case 'update_task':
                $id = $answer['data']['id'];
                unset($answer['data']['id']);
                $task = $this->taskRepository->find($id);
                return [
                    'action' => $answer['action'],
                    'task' => $this->update($task, $answer['data']),
                    'message' => 'We are updating task: ' . $task->title
                ];
            case 'delete_task':
                $id = $answer['data']['id'];
                unset($answer['data']['id']);
                $task = $this->taskRepository->find($id);
                return [
                    'action' => $answer['action'],
                    'task' => null,
                    'message' => 'We are deleting task: ' . $task->title
                ];
            case 'unknown':
                return [
                    'action' => $answer['action'],
                    'task' => null,
                    'message' => 'We can\'t define your request'
                    ];
            default:
                throw new RuntimeException('Unknown action: '. ($answer['action'] ?? null));
        }
    }
}
