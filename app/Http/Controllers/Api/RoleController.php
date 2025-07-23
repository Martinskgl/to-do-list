<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index (): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $roles = $this->roleService->getAll();

        return response()->json($roles);
    }
}