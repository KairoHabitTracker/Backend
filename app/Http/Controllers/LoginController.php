<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController
{
    public function index()
    {
        return inertia('Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'password' => ['required'],
        ]);

        if (auth()->attempt([
            'email' => config('app.admin_email'),
            'password' => $credentials['password']
        ])) {
            $request->session()->regenerate();
            return to_route('dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('password');
    }

    public function destroy(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login');
    }
}
