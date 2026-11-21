<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('staff.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'NIP atau Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $credentials['login'];
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Check whether input is email or nip
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';

        // Check if user exists
        $user = User::where($loginField, $loginInput)->first();

        if (! $user) {
            return back()->withInput($request->only('login', 'remember'))->withErrors([
                'login' => 'NIP atau Email tidak terdaftar dalam sistem.',
            ]);
        }

        if (! $user->status_aktif) {
            return back()->withInput($request->only('login', 'remember'))->withErrors([
                'login' => 'Akun Anda berstatus non-aktif. Silakan hubungi Administrator Kelurahan.',
            ]);
        }

        if (Auth::attempt([$loginField => $loginInput, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang kembali di SICAKRA, '.Auth::user()->name.'.');
            }

            return redirect()->intended(route('staff.dashboard'))
                ->with('success', 'Selamat datang di SICAKRA, '.Auth::user()->name.'.');
        }

        return back()->withInput($request->only('login', 'remember'))->withErrors([
            'password' => 'Kata sandi yang Anda masukkan tidak sesuai.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem SICAKRA.');
    }
}
