<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Debe ingresar su correo electrónico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Se generó el enlace de recuperación. Revise el log del sistema para probarlo.')
            : back()->withErrors(['email' => 'No se encontró una cuenta registrada con ese correo.']);
    }
}
