<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AutenticacionController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);


        $usuario = Registro::where('correo', $request->correo)->first();


        if ($usuario && $usuario->contrasena === $request->contrasena) {
            session([
                'registro_id' => $usuario->id,
                'usuario_registrado' => $usuario->nombre,
                'usuario_logueado' => true
            ]);


            return Redirect::route('publicaciones.index');
        }


        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout()
    {
        session()->forget(['registro_id', 'usuario_registrado', 'usuario_logueado']);
        
        return Redirect::route('auth.login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }

    public function dashboard(): View
    {
        $usuarioId = session('registro_id');
        

        if (!$usuarioId) {
            return Redirect::route('auth.login');
        }


        $usuario = Registro::find($usuarioId);
        

        $publicacionesUsuario = \App\Models\Publicacione::where('registro_id', $usuarioId)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('auth.dashboard', compact('usuario', 'publicacionesUsuario'));
    }
}
