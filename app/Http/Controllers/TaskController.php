<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->index($request->user());

        return response()->json($tasks, 200);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->store(
            $request->user(),
            $request->validated(),
        );

        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        $task = $this->taskService->show($task);

        return response()->json($task, 200);
    }

    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->update($task, $request->validated());

        return response()->json($task, 200);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->destroy($task);

        return response()->json([
            'message' => 'Task deleted successfully',
            'id' => $task->id,
        ], 200);
    }

    public function interpret(Request $request)
    {
        $task = $this->taskService->interpret($request->user(), $request->input('text'), $request->input('confidence'));

        return response()->json($task, 200);
    }
}
