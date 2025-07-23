<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RoleService
{
    protected RoleRepository $repository;

    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->all();
    }
}