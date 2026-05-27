<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LugarVisitadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'destino_id'   => 'required|exists:destinos,id',
            'fecha_visita' => 'nullable|date|before_or_equal:today',
            'notas'        => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'destino_id.required'        => 'El destino es obligatorio.',
            'destino_id.exists'          => 'El destino seleccionado no existe.',
            'fecha_visita.before_or_equal' => 'La fecha de visita no puede ser futura.',
            'notas.max'                  => 'Las notas no pueden exceder 500 caracteres.',
        ];
    }
}
