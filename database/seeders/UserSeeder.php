<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password',
            'role_id' => $userRole->id,
        ]);
    }
}
