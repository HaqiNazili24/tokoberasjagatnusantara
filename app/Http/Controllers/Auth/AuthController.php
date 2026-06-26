<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($data, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $request->session()->regenerate();
        
        $user = Auth::user();
        if ($user->isOwner()) {
            return redirect()->intended(route('owner.dashboard'));
        } elseif ($user->isKaryawan()) {
            return redirect()->intended(route('karyawan.dashboard'));
        } elseif ($user->isKurir()) {
            return redirect()->intended(route('kurir.dashboard'));
        }
        
        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'numeric', 'digits_between:10,15'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);
        $data['role'] = 'customer';
        User::create($data);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
