<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RutaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'                  => 'required|string|max:150',
            'descripcion'             => 'nullable|string|max:1000',
            'origen'                  => 'required|string|max:150',
            'destino'                 => 'required|string|max:150',
            'tiempo_estimado_minutos' => 'required|integer|min:1',
            'costo_aproximado_soles'  => 'required|numeric|min:0',
            'color_linea'             => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'                  => 'El nombre de la ruta es obligatorio.',
            'origen.required'                  => 'El punto de origen es obligatorio.',
            'destino.required'                 => 'El destino es obligatorio.',
            'tiempo_estimado_minutos.required' => 'El tiempo estimado es obligatorio.',
            'tiempo_estimado_minutos.min'      => 'El tiempo estimado debe ser al menos 1 minuto.',
            'costo_aproximado_soles.required'  => 'El costo aproximado es obligatorio.',
            'color_linea.regex'                => 'El color debe ser un código hexadecimal válido (#RRGGBB).',
        ];
    }
}
