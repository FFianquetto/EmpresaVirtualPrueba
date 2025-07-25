<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'nombre' => 'required|string',
			'correo' => 'required|email|unique:registros,correo',
			'contrasena' => 'required|string',
			'edad' => 'required|integer',
        ];
    }


    public function messages(): array
    {
        return [
            'correo.unique' => 'Correo ya registrado.',
            'correo.email' => 'Formato inválido',
            'nombre.required' => 'El nombre es obligatorio.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.integer' => 'La edad debe ser un número',
        ];
    }
}
