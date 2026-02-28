<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('full_name')->paginate(25);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,coach,client',
        ]);

        $user = DB::transaction(function () use ($validated) {
            return User::create($validated);
        });

        Log::info('User created', ['user_id' => $user->id, 'role' => $user->role, 'by' => Auth::id()]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'full_name' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,coach,client',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        DB::transaction(function () use ($user, $validated) {
            $user->update($validated);
        });

        Log::info('User updated', ['user_id' => $user->id, 'by' => Auth::id()]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        Log::warning('User deleted', ['user_id' => $user->id, 'username' => $user->username, 'by' => Auth::id()]);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
