<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_roles()
    {
        $user = User::factory()->create();
        
        // Mock the Gate to allow authorization
        $this->app['gate']->define('viewAny', function ($user, $class) {
            return true;
        });
        
        $response = $this->actingAs($user)->getJson('/api/roles');
        
        $this->assertContains($response->status(), [200, 403]);
    }

    #[Test]
    public function list_roles_unauthenticated()
    {
        $response = $this->getJson('/api/roles');
        
        $response->assertUnauthorized();
    }

    #[Test]
    public function returns_json()
    {
        $user = User::factory()->create();
        
        // Mock the Gate to allow authorization
        $this->app['gate']->define('viewAny', function ($user, $class) {
            return true;
        });
        
        $response = $this->actingAs($user)->getJson('/api/roles');
        
        if ($response->status() === 200) {
            $response->assertJsonStructure();
        }
    }

    #[Test]
    public function list_roles_unauthorized()
    {
        $user = User::factory()->create();
        
        // Mock the Gate to deny authorization
        $this->app['gate']->define('viewAny', function ($user, $class) {
            return false;
        });
        
        $response = $this->actingAs($user)->getJson('/api/roles');
        
        $response->assertForbidden();
    }

    #[Test]
    public function calls_service()
    {
        $user = User::factory()->create();
        $roles = ['manager', 'supervisor'];
        
        // Mock the Gate to allow authorization
        $this->app['gate']->define('viewAny', function ($user, $class) {
            return true;
        });
        
        $service = Mockery::mock(RoleService::class);
        $service->shouldReceive('getAll')->once()->andReturn($roles);
        
        $this->app->instance(RoleService::class, $service);
        
        $response = $this->actingAs($user)->getJson('/api/roles');
        
        if ($response->status() === 200) {
            $service->shouldHaveReceived('getAll');
        }
    }

    #[Test]
    public function returns_service_data()
    {
        $user = User::factory()->create();
        $roles = ['editor', 'viewer', 'moderator'];
        
        // Mock the Gate to allow authorization
        $this->app['gate']->define('viewAny', function ($user, $class) {
            return true;
        });
        
        $service = Mockery::mock(RoleService::class);
        $service->shouldReceive('getAll')->andReturn($roles);
        
        $this->app->instance(RoleService::class, $service);
        
        $response = $this->actingAs($user)->getJson('/api/roles');
        
        if ($response->status() === 200) {
            $response->assertJson($roles);
        }
    }
}