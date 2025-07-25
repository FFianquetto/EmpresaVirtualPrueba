<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicacioneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'Título obligatorio',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'contenido.required' => 'Descripción obligatoria',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg',
            'imagen.max' => 'La imagen no puede ser mayor a 5MB.',
            'audio.file' => 'El archivo debe ser un archivo de audio.',
            'audio.mimes' => 'El audio debe ser de tipo: mp3, wav, ogg',
            'audio.max' => 'El audio no puede ser mayor a 20MB.',
        ];
    }
}
