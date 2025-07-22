<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function all(): Collection
    {
        return Task::all();
    }

    public function find(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        return $task->update($data);
    }

    public function delete(int $id): bool
    {
        $task = Task::find($id);
        if (!$task) {
            return false;
        }
        return $task->delete();
    }

    public function findByUser(int $userId): Collection
    {
        return Task::where('user_id', $userId)->get();
    }

    public function findByStatus(string $status): Collection
    {
        return Task::where('status', $status)->get();
    }
}