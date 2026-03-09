<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(UserCreateRequest $request)
    {
        $data = $request->validated();
        try {
            $user = User::create($data);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'message' => 'Unable to save user.',
                'errors' => ['email' => ['This email address is already in use.']],
            ], 422);
        }

        return new UserResource($user);
    }

    public function update(User $user, UserCreateRequest $request)
    {
        $data = $request->validated();
        $user->update($data);
        return new UserResource($user);
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json(['message' => 'Password updated. Please log in again.']);
    }
}
