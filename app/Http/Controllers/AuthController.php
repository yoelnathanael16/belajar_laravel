<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    private const MESSAGES = [
        'required' => ':attribute wajib diisi.',
        'email' => 'Format email belum benar.',
        'email.unique' => 'Email ini sudah terdaftar. Silakan masuk.',
        'no_whatsapp.regex' => 'Nomor WhatsApp belum benar, contoh: 081234567890.',
        'password.min' => 'Kata sandi minimal :min karakter.',
    ];

    private const ATTRIBUTES = [
        'nama' => 'Nama lengkap',
        'email' => 'Email',
        'no_whatsapp' => 'Nomor WhatsApp',
        'password' => 'Kata sandi',
    ];

    public function login(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], self::MESSAGES, self::ATTRIBUTES);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended($request->user()->homeUrl());
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_whatsapp' => ['required', 'regex:/^(\+62|62|0)8\d{7,12}$/'],
            'password' => ['required', Password::min(8)],
        ], self::MESSAGES, self::ATTRIBUTES);

        // role tidak diisi di sini, jadi selalu "customer" (nilai default di database)
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('profile'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
