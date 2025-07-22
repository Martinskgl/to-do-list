<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index (): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userService->getAll();

        return response()->json($users->load('role'));
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json($user->load('role'));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);
    
        $user = $this->userService->create($request->validated());

        $this->userService->assignRole($user->id, $request->role_id);

        return response()->json($user, 201);   
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $this->userService->update($user->id, $request->validated());
        
        $this->userService->assignRole($user->id, $request->role_id);

        $updatedUser = $this->userService->getById($user->id);

        return response()->json($updatedUser);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->userService->delete($user->id);

        return response()->json(['message' => 'Usuário deletado com sucesso']);
    }
}