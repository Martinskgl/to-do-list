<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleRepository
{
    public function getById(int $id): ?Role
    {
        return Role::find($id);
    }
}