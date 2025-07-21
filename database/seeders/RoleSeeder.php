<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['slug' => 'admin', 'label' => 'admin']);
        Role::create(['slug' => 'user', 'label' => 'user']);
    }
}