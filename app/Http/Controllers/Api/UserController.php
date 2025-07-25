<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('role')->paginate(15);

        return response()->json($users->load('role'));
    }

    public function show(User $user): JsonResponse
    {
        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user->load('role'));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        if ($request->role_id) {
            User::where('id', $user->id)
                ->update(['role_id' => $request->role_id]);
        }

        return response()->json($data, 201);
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
