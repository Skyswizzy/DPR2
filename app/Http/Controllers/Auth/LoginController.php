<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Handles administrator login and logout.
 * There is no public registration: administrators are created by the seeder.
 */
class LoginController extends Controller
{
    private const MAX_ATTEMPTS = 5;

    public function create(): View|RedirectResponse
    {
        // Already signed in? Go straight to the inventory.
        if (Auth::check()) {
            return redirect()->route('equipment.index');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Enter your email address or username.',
            'password.required' => 'Enter your password.',
        ]);

        // Brute-force protection: limit attempts per login name + IP address.
        $throttleKey = Str::lower($credentials['login']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        // Business logic: if the input looks like an email address we match the
        // email column, otherwise we treat it as a username.
        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $authenticated = Auth::attempt(
            [$field => $credentials['login'], 'password' => $credentials['password']],
            $request->boolean('remember')
        );

        if (! $authenticated) {
            RateLimiter::hit($throttleKey);

            // One generic message so we never reveal which half was wrong.
            throw ValidationException::withMessages([
                'login' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Prevents session fixation after a successful login.
        $request->session()->regenerate();

        return redirect()->intended(route('equipment.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Destroy the session and issue a fresh CSRF token for a secure logout.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
