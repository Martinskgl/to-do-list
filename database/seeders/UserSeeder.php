<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole  = Role::where('slug', 'user')->first();

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);

        User::create([
            'name'     => 'User',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id'  => $userRole->id,
        ]);
    }
}
