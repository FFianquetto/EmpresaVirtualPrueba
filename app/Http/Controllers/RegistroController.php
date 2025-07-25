<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function create(): View
    {
        $registro = new Registro();
        return view('registro.create', compact('registro'));
    }

    public function store(RegistroRequest $request): RedirectResponse
    {
        $registro = Registro::create($request->validated());
        return Redirect::route('auth.login')
            ->with('success', '¡Registro exitoso! Ahora puedes iniciar sesión con tu correo y contraseña.');
    }
}
