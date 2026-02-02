<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectBasedOnRole(Auth::user()));
        }

        $devUsers = [];
        if (app()->environment('local')) {
            $devUsers = User::orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'coach' THEN 2 WHEN 'client' THEN 3 ELSE 4 END")
                ->get(['id', 'username', 'full_name', 'role']);
        }

        return view('auth.login', compact('devUsers'));
    }

    public function devLogin(Request $request)
    {
        abort_unless(app()->environment('local'), 403);

        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::findOrFail($request->user_id);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect($this->redirectBasedOnRole($user));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectBasedOnRole(Auth::user()));
        }

        return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectBasedOnRole($user): string
    {
        return match ($user->role) {
            'client' => '/portal',
            default => '/dashboard',
        };
    }
}
