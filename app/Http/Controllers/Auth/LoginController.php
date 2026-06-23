<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        $limiter = app(RateLimiter::class);

        if ($limiter->tooManyAttempts($throttleKey, 5)) {
            $seconds = $limiter->availableIn($throttleKey);
            return back()
                ->withErrors(['email' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos."])
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $limiter->clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        $limiter->hit($throttleKey, 60);

        return back()
            ->withErrors(['email' => 'Las credenciales ingresadas no son correctas.'])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
