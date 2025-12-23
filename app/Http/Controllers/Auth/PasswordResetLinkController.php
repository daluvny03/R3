<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
    // Validasi email
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    // Cari user berdasarkan email
    $user = User::where('email', $request->email)->first();

    if (! $user) {
        return back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    // Buat token reset password tanpa mengirim email
    $token = Password::createToken($user);

    // Buat URL reset password
    $resetUrl = url(route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ], false));

    // Kembalikan link ke user
    return back()->with('status', 'Link reset password berhasil dibuat.')
                 ->with('reset_link', $resetUrl);
}
}
