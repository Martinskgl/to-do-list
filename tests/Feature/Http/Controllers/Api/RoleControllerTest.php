<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_mock()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Gate::shouldReceive('authorize')->once()->andReturnTrue();

        $mock = Mockery::mock(RoleService::class);
        $mock->shouldReceive('getAll')->once()->andReturn([]);
        $this->app->instance(RoleService::class, $mock);

        $res = $this->getJson(route('roles.index'));
        $res->assertOk()->assertJson([]);
    }

    public function test_all()
    {
        $this->withoutMiddleware(); // desativa a policy

        $user = User::factory()->create();
        $this->actingAs($user);

        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Editor'],
        ];

        $mock = Mockery::mock(RoleService::class);
        $mock->shouldReceive('getAll')->once()->andReturn($roles);

        $this->app->instance(RoleService::class, $mock);

        $res = $this->getJson(route('roles.index'));

        $res->assertOk()->assertJson($roles);
    }

    public function test_unauthz()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Gate::shouldReceive('authorize')
        ->once()
        ->andThrow(new \Illuminate\Auth\Access\AuthorizationException());

        $res = $this->getJson(route('roles.index'));
        $res->assertForbidden();
    }

    public function test_guest()
    {
        $res = $this->getJson(route('roles.index'));
        $res->assertUnauthorized(); // 401
    }

    public function test_err()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Gate::shouldReceive('authorize')->once()->andReturnTrue();

        Log::shouldReceive('error')->once(); // Opcional, se loga o erro

        $mock = Mockery::mock(RoleService::class);
        $mock->shouldReceive('getAll')->once()->andThrow(new \Exception('Falha interna'));
        $this->app->instance(RoleService::class, $mock);

        $res = $this->getJson(route('roles.index'));
        $res->assertStatus(500);
    }
}
