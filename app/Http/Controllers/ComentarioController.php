<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ComentarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ComentarioController extends Controller
{
    public function index(Request $request): View
    {
        $usuarioId = session('registro_id');
        

        if (!$usuarioId) {
            return redirect()->route('auth.login')->with('error', 'Debes iniciar sesión para ver tus conversaciones.');
        }
        

        return view('comentario.index')
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function create(Request $request): View
    {
        if (!session('usuario_logueado')) {
            return redirect()->route('auth.login')->with('error', 'Debes iniciar sesión para enviar mensajes.');
        }
        
        $comentario = new Comentario();
        $emisorId = $request->get('emisor_id');
        $receptorId = $request->get('receptor_id');
        $publicacionId = $request->get('publicacion_id');
        $emisor = null;
        $receptor = null;
        $publicacion = null;
        
        if ($emisorId) {
            $emisor = \App\Models\Registro::find($emisorId);
        }
        
        if ($receptorId) {
            $receptor = \App\Models\Registro::find($receptorId);
        }
        
        if ($publicacionId) {
            $publicacion = \App\Models\Publicacione::find($publicacionId);
        }
        return view('comentario.create', compact('comentario', 'emisor', 'receptor', 'publicacion'));
    }

    public function store(ComentarioRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $emisorId = $request->input('emisor_id');
        $receptorId = $request->input('receptor_id');
        

        if ($emisorId) {
            $data['registro_id_emisor'] = $emisorId;
        }
        

        if ($receptorId) {
            $data['registro_id_receptor'] = $receptorId;
        }


        if (!isset($data['registro_id_emisor'])) {
            $data['registro_id_emisor'] = session('registro_id');
        }


        if (!isset($data['registro_id_receptor'])) {
            return back()->withErrors(['mensaje' => 'Debes seleccionar un destinatario.']);
        }


        if ($data['registro_id_emisor'] == $data['registro_id_receptor']) {
            return back()->withErrors(['mensaje' => 'No puedes enviar mensajes a ti mismo.']);
        }


        if (!session('usuario_logueado')) {
            return back()->withErrors(['mensaje' => 'Debes iniciar sesión para enviar mensajes.']);
        }

        $mensaje = trim($request->input('mensaje', ''));
        $imagen = $request->file('imagen');
        $audio = $request->file('audio');
        
        if (empty($mensaje) && !$imagen && !$audio) {
            return back()->withErrors(['mensaje' => 'Debes enviar al menos un mensaje, imagen o audio.']);
        }

        if (empty($mensaje)) {
            $data['mensaje'] = null;
        }

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenPath = $imagen->store('comentarios/imagenes', 'public');
            $data['imagen'] = $imagenPath;
        }

        if ($request->hasFile('audio')) {
            $audio = $request->file('audio');
            $audioPath = $audio->store('comentarios/audios', 'public');
            $data['audio'] = $audioPath;
        }

        Comentario::create($data);

        return Redirect::route('comentarios.conversacion', [$data['registro_id_emisor'], $data['registro_id_receptor']]);
    }

    public function show($id): View
    {
        $comentario = Comentario::find($id);
        return view('comentario.show', compact('comentario'));
    }

    public function conversacion($usuario1, $usuario2): View
    {
        $usuarioActual = session('registro_id');
        if (!$usuarioActual) {
            return redirect()->route('auth.login')->with('error', 'Debes iniciar sesión para ver conversaciones.');
        }
        if ($usuarioActual != $usuario1 && $usuarioActual != $usuario2) {
            return redirect()->route('comentarios.index')->with('error', 'No tienes permisos para ver esta conversación.');
        }
        $conversacion = Comentario::where(function($query) use ($usuario1, $usuario2) {
            $query->where('registro_id_emisor', $usuario1)
                  ->where('registro_id_receptor', $usuario2);
        })->orWhere(function($query) use ($usuario1, $usuario2) {
            $query->where('registro_id_emisor', $usuario2)
                  ->where('registro_id_receptor', $usuario1);
        })->with(['emisor', 'receptor'])
          ->orderBy('created_at', 'asc')
          ->get();
        $otroUsuario = $usuarioActual == $usuario1 ? $usuario2 : $usuario1;
        $otroUsuarioData = \App\Models\Registro::find($otroUsuario);
        return view('comentario.conversacion', compact('conversacion', 'otroUsuarioData', 'usuarioActual'));
    }
}
