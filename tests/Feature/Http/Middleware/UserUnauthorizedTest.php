<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }

    public function test_user_admin()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
    }
}
