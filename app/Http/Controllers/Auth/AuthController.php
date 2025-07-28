<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Login berhasil!');
        }
        return back()->withErrors([
            'email' => 'Email atau password salah. Silahkan coba lagi.'
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'profession' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ]);

        User::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guest',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'profession' => $request->profession,
            'address' => $request->address,
        ]);

        return redirect()->route('show.login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show.login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
