<?php

namespace App\Repositories;

use App\Models\{User, Role};
use Illuminate\Database\Eloquent\Collection;

class UserRepository 
{ 
    public function all(): Collection
    {
        return User::all();
    }

    public function create(array $data): ?User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }
        return User::where('id', $id)->update($data);
    }

    public function delete(int $id): bool 
    { 
        return User::where('id', $id)->delete();
    }

    public function assignRole(int $id, Role $role)
    { 
        return User::where('id', $id)->update(['role_id' => $role->id]);
    }

    public function getRole(int $id): ?Role
    {
        return User::where('id', $id)->with('role')->first()?->role;
    }

    public function findByName(string $name): ?User
    {
        return User::where('name', $name)->first();
    }

    public function searchByName(string $name): Collection
    {
        return User::where('name', 'like', "%$name%")->with('role')->get();
    }

    public function findById(int $id): ?User
    {
        return User::with('role')->find($id);
    }
}