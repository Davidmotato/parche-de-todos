<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Digita el correo.',
            'email.email' => 'El correo no es válido.',
            'password.required' => 'Escriba la contraseña.',
        ]);

        //se permite el ingreso a usuarios activos unicamente
        $credenciales['estado'] = 'activo';

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->route('inicio');
        }

        return back()
        ->withErrors(['email' => 'Correo o contraseña incorrectos'])
        ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
