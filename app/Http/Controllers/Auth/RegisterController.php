<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        // Transacción explícita: la creación del usuario y su perfil
        // deben ser atómicas. Si el perfil (user_profiles) falla al
        // crearse, el registro del usuario no debería persistir para
        // evitar usuarios huérfanos sin perfil.
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'puntaje_exploracion' => 0,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()
            ->route('home')
            ->with('success', "¡Bienvenido a GoWayki, {$user->name}!");
    }
}
