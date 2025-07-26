<?php

namespace App\Http\Controllers;

use App\Models\Publicacione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PublicacioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicacioneController extends Controller
{
    public function index(Request $request): View
    {
        $publicaciones = Publicacione::with('autor')->paginate();
        $usuarioRegistrado = session('usuario_registrado');
        $registroId = session('registro_id');
        
        return view('publicacione.index', compact('publicaciones', 'usuarioRegistrado', 'registroId'))
            ->with('i', ($request->input('page', 1) - 1) * $publicaciones->perPage());
    }

    public function create(): View
    {
        $publicacione = new Publicacione();
        $registroId = session('registro_id');
        return view('publicacione.create', compact('publicacione', 'registroId'));
    }

    public function store(PublicacioneRequest $request): RedirectResponse
    {
        $data = $request->validated();
        

        if (!isset($data['registro_id']) || empty($data['registro_id'])) {
            $registroId = session('registro_id');
            

            if (!$registroId) {
                $primerRegistro = \App\Models\Registro::first();
                if ($primerRegistro) {
                    $data['registro_id'] = $primerRegistro->id;
                } else {
                    return Redirect::route('registros.create')
                        ->with('error', 'Debes registrarte primero para crear publicaciones.');
                }
            } else {
                $data['registro_id'] = $registroId;
            }
        }


        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('publicaciones/imagenes', 'public');
            $data['imagen'] = $imagenPath;
        }


        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('publicaciones/audios', 'public');
            $data['audio'] = $audioPath;
        }
        

        Publicacione::create($data);
        

        return Redirect::route('publicaciones.index')
            ->with('success', 'Publicación creada.');
    }

    public function show($id): View
    {
        $publicacione = Publicacione::find($id);
        return view('publicacione.show', compact('publicacione'));
    }

    public function edit($id): View
    {
        $publicacione = Publicacione::find($id);
        $registroId = session('registro_id');
        
        if (!$registroId || $publicacione->registro_id != $registroId) {
            return Redirect::route('publicaciones.index')
                ->with('error', 'No tienes permisos para editar esta publicación.');
        }
        
        return view('publicacione.edit', compact('publicacione', 'registroId'));
    }

    public function update(PublicacioneRequest $request, $id): RedirectResponse
    {
        $publicacione = Publicacione::find($id);
        $registroId = session('registro_id');
        

        if (!$registroId || $publicacione->registro_id != $registroId) {
            return Redirect::route('publicaciones.index')
                ->with('error', 'No tienes permisos para actualizar esta publicación.');
        }
        

        $data = $request->validated();


        if ($request->hasFile('imagen')) {
            if ($publicacione->imagen && Storage::disk('public')->exists($publicacione->imagen)) {
                Storage::disk('public')->delete($publicacione->imagen);
            }
            $imagenPath = $request->file('imagen')->store('publicaciones/imagenes', 'public');
            $data['imagen'] = $imagenPath;
        }


        if ($request->hasFile('audio')) {
            if ($publicacione->audio && Storage::disk('public')->exists($publicacione->audio)) {
                Storage::disk('public')->delete($publicacione->audio);
            }
            $audioPath = $request->file('audio')->store('publicaciones/audios', 'public');
            $data['audio'] = $audioPath;
        }
        

        $publicacione->update($data);
        

        return Redirect::route('publicaciones.index')
            ->with('success', 'Publicación actualizada.');
    }

    public function destroy($id): RedirectResponse
    {
        $publicacione = Publicacione::find($id);
        $registroId = session('registro_id');
        
        if (!$registroId || $publicacione->registro_id != $registroId) {
            return Redirect::route('publicaciones.index')
                ->with('error', 'No tienes permisos para eliminar esta publicación.');
        }

        if ($publicacione->imagen && Storage::disk('public')->exists($publicacione->imagen)) {
            Storage::disk('public')->delete($publicacione->imagen);
        }
        
        if ($publicacione->audio && Storage::disk('public')->exists($publicacione->audio)) {
            Storage::disk('public')->delete($publicacione->audio);
        }
        
        $publicacione->delete();
        
        return Redirect::route('publicaciones.index')
            ->with('success', 'Publicación borrada.');
    }
}
