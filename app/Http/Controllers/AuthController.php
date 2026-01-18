<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && password_verify($credentials['password'], $user->password)) {
            session(['user_id' => $user->id, 'full_name' => $user->full_name]);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['username' => 'Invalid credentials.'])->withInput();
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
