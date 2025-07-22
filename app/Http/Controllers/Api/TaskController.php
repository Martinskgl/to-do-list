<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use App\Models\Task;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);
        
        $perPage = $request->get('per_page', 15);
        $tasks = $this->service->getAll($perPage);
        
        return response()->json($tasks);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);
        
        return response()->json($task);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);
        
        $task = $this->service->create($request->validated(), $request->user()->id);
        
        return response()->json($task, 201);
    }

    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        
        $this->service->update($task->id, $request->validated());
        $updatedTask = $this->service->getById($task->id);
        
        return response()->json($updatedTask);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);
        
        $this->service->delete($task->id);
        
        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        
        $request->validate(['status' => 'required|string']);
        
        $this->service->update($task->id, ['status' => $request->status]);
        $updatedTask = $this->service->getById($task->id);
        
        return response()->json($updatedTask);
    }
}