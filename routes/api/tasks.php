<?php

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])
        ->can('viewAny', Task::class);
    Route::post('tasks', [TaskController::class, 'store'])
        ->can('create', Task::class);
    Route::get('tasks/{task}', [TaskController::class, 'show'])
        ->can('view', 'task');
    Route::patch('tasks/{task}', [TaskController::class, 'update'])
        ->can('update', 'task');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
        ->can('delete', 'task');
});
