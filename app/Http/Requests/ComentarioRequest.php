<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComentarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mensaje' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'mensaje.max' => 'El mensaje no puede tener más de 1000 caracteres.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'imagen.max' => 'La imagen no puede ser mayor a 2MB.',
            'audio.file' => 'El archivo debe ser un archivo de audio.',
            'audio.mimes' => 'El audio debe ser de tipo: mp3, wav, ogg.',
            'audio.max' => 'El audio no puede ser mayor a 10MB.',
        ];
    }
}
