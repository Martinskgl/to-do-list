<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class TaskService
{
    protected TaskRepository $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function getById(int $id): ?Task
    {
        return $this->repository->find($id);
    }

    public function create(array $data, int $userId): Task
    {
        $data['slug'] = $this->generateSlug($data['title']);
        $data['user_id'] = $userId;
        
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getUser(int $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function getByStatus(string $status): Collection
    {
        return $this->repository->findByStatus($status);
    }

    private function generateSlug(string $title): string
    {
        return Str::slug($title);
    }
}