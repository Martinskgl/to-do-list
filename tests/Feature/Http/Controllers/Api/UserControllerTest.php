<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    public function test_index_unauthenticated()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }

    public function test_index_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }

    public function test_show()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $response->json());
    }

    public function test_show_unauthenticated()
    {
        $user = User::factory()->create();
        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(401);
    }

    public function test_show_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/users/999');
        $response->assertStatus(404);
        $this->assertArrayHasKey('message', $response->json());
    }

    public function test_show_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}");
        $response->assertStatus(401);
    }

    public function test_store()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'testete',
            'email' => 'testee@gmail.com',
            'password' => 'inicio@1',
            'role_id' => $role->id,
        ];

        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(201);

        $json = $response->json();
        $this->assertIsArray($json);

    }

    public function test_store_unauthenticated()
    {
        $response = $this->postJson('/api/users', []);
        $response->assertStatus(401);
    }

    public function test_store_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $data = [
            'name' => 'testete',
            'email' => 'testee@gmail.com',
            'password' => 'inicio@1',
            'role_id' => $role->id,
        ];

        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(401);
    }

    public function test_update()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'updated name',
            'email' => 'updated_email@example.com',
            'password' => 'updated_password',
            'role_id' => $role->id,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertIsArray($json);
        $this->assertEquals($data['name'], $json['name']);
        $this->assertEquals($data['email'], $json['email']);
    }

    public function test_update_unauthenticated()
    {
        $user = User::factory()->create();
        $response = $this->putJson("/api/users/{$user->id}", []);
        $response->assertStatus(401);
    }

    public function test_update_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $data = [
            'name' => 'updated name',
            'email' => 'updated_email@example.com',
            'password' => 'updated_password',
            'role_id' => $role->id,
        ];

        $response = $this->putJson('/api/users/999', $data);
        $response->assertStatus(404);
        $this->assertArrayHasKey('message', $response->json());
    }

    public function test_update_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $data = [
            'name' => 'updated name',
            'email' => 'updated_email@example.com',
            'password' => 'updated_password',
            'role_id' => $role->id,
        ];

        $response = $this->putJson("/api/users/{$user->id}", $data);
        $response->assertStatus(401);
        $this->assertArrayHasKey('message', $response->json());
    }

    public function test_destroy()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_destroy_unauthenticated()
    {
        $user = User::factory()->create();
        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(401);
    }

    public function test_destroy_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson('/api/users/999');
        $response->assertStatus(404);
        $this->assertArrayHasKey('message', $response->json());
    }

    public function test_destroy_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");
        $response->assertStatus(401);
    }
}
