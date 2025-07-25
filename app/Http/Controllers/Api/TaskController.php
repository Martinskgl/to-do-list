<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Jobs\ExportTasksToCsv;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::with('user')->paginate(15);

        return response()->json($tasks);
    }

    public function show(Task $task): JsonResponse
    {
        if (! $task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->load('user');

        return response()->json($task);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $task = Task::create([
            ...$request->validated(),
            'slug' => Str::slug($request->input('title')),
        ]);

        return response()->json($task, 201);
    }

    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $data = $request->validated();

        if (! $task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $data['slug'] = Str::slug($data['title']);

        $task->fill($data);
        $task->save();

        $task->load('user');

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        if (! $task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $request->validate(['status' => 'sometimes|string|in:pending,in_progress,completed,cancelled']);
        if (! $task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->update(['status' => $request->status]);

        return response()->json($task);
    }

    public function getByUser(): JsonResponse
    {
        $tasks = Task::where('user_id', auth()->user()->id)->with('user')->get();

        if (! $tasks) {
            return response()->json(['message' => 'No tasks found for this user'], 404);
        }

        return response()->json($tasks);
    }

    public function exportTasks()
    {
        $userId = auth()->id();

        (new ExportTasksToCsv($userId))->handle();
        $file = storage_path("app/exports/tasks_{$userId}.csv");
        if (! file_exists($file)) {
            return response()->json(['message' => 'Arquivo não encontrado'], 404);
        }

        return response()->download($file, 'tasks.csv')->deleteFileAfterSend(true);
    }
}
