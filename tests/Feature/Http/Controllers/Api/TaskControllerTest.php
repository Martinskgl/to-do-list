<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_index_unathenticated()
    {
        $response = $this->getJson(route('tasks.index'));
        $response->assertStatus(401);
    }

    public function test_index_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    public function test_show()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/'.$task->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'title',
            'user_id',
        ]);
    }

    public function test_show_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/tasks/9999');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Task] 9999']);
    }

    public function test_show_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/'.$task->id);
        $response->assertStatus(401);
    }

    public function test_store()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $data = [
            'title'           => 'New Task',
            'describe'        => 'Task description',
            'user_id'         => $user->id,
            'expiration_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'title', 'describe', 'expiration_date']);
    }

    public function test_store_unauthenticated()
    {
        $data = [
            'title'           => 'New Task',
            'describe'        => 'Task description',
            'user_id'         => 1,
            'expiration_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(401);
    }

    public function test_store_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $data = [
            'title'           => 'New Task',
            'describe'        => 'Task description',
            'user_id'         => $user->id,
            'expiration_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/tasks', $data);
        $response->assertStatus(401);
    }

    public function test_update()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $data = [
            'title'           => 'Updated Task',
            'describe'        => 'Updated description',
            'expiration_date' => now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->putJson('/api/tasks/'.$task->id, $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'title', 'describe', 'expiration_date']);
    }

    public function test_update_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $data = [
            'title'           => 'Updated Task',
            'describe'        => 'Updated description',
            'expiration_date' => now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->putJson('/api/tasks/9999', $data);
        $response->assertStatus(404);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Task] 9999']);
    }

    public function test_update_unauthenticated()
    {
        $task = Task::factory()->create();

        $data = [
            'title'           => 'Updated Task',
            'describe'        => 'Updated description',
            'expiration_date' => now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->putJson('/api/tasks/'.$task->id, $data);
        $response->assertStatus(401);
    }

    public function test_update_role_forbidden()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $data = [
            'title'           => 'Updated Task',
            'describe'        => 'Updated description',
            'expiration_date' => now()->addDays(10)->toDateTimeString(),
        ];

        $response = $this->putJson('/api/tasks/'.$task->id, $data);
        $response->assertStatus(401);
    }

    public function test_destroy()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/tasks/'.$task->id);
        $response->assertStatus(204);
    }

    public function test_destroy_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->deleteJson('/api/tasks/9999');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Task] 9999']);
    }

    public function test_destroy_unauthenticated()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson('/api/tasks/'.$task->id);
        $response->assertStatus(401);
    }

    public function test_destroy_role()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/tasks/'.$task->id);
        $response->assertStatus(204);
    }

    public function test_update_status()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->patchJson("/api/tasks/{$task->id}/status", ['status' => 'completed']);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'completed']);
    }

    public function test_update_status_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->patchJson('/api/tasks/9999/status', ['status' => 'completed']);
        $response->assertStatus(404);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Task] 9999']);
    }

    public function test_update_status_unauthenticated()
    {
        $task = Task::factory()->create();

        $response = $this->patchJson("/api/tasks/{$task->id}/status", ['status' => 'completed']);
        $response->assertStatus(401);
    }

    public function test_update_status_role()
    {
        $role = Role::factory()->create(['slug' => 'user', 'label' => 'user']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->patchJson("/api/tasks/{$task->id}/status", ['status' => 'completed']);
        $response->assertStatus(200);
    }

    public function teste_update_invalid_status()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->patchJson("/api/tasks/{$task->id}/status", ['status' => 'invalid_status']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    public function test_get_by_user()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/user');
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $task->id]);
    }

    public function test_get_by_user_not_found()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/tasks/9');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'No query results for model [App\\Models\\Task] 9']);
    }

    public function test_export_tasks()
    {
        $role = Role::factory()->create(['slug' => 'admin', 'label' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($user);

        Task::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
