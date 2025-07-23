<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;


class UserService 
{ 
    protected UserRepository $userRepository;
    protected RoleRepository $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getAll(): Collection
    {
        return $this->userRepository->all();
    }

    public function getByName(string $name): ?User
    {
        return $this->userRepository->findByName($name);
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }

    public function assignRole(int $id, int $role): bool
    {
        $role = $this->roleRepository->getById($role);

        return $this->userRepository->assignRole($id, $role);
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function searchByName(string $name): Collection
    {
        return $this->userRepository->searchByName($name);
    }
}