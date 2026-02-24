<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(Request $request){
        $request->validate([
            'employee_id' => ['required','uuid'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);
        $user = User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
        ]);
    }

    public function updateAccount(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function updatePassword(Request $request) {
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

        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return response()->json(['message' => 'Password updated. Please log in again.']);
    }
}
