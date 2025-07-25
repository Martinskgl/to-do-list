<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/roles');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_index_unauthenticated()
    {
        $response = $this->getJson(route('roles.index'));
        $response->assertStatus(401);
    }

    public function test_index_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson(route('roles.index'));
        $response->assertStatus(401);
    }
}
