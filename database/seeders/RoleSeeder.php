<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['slug' => 'admin', 'label' => 'admin']);
        Role::create(['slug' => 'user', 'label' => 'user']);
    }
}
