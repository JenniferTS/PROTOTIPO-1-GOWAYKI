<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecorridoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'origen'  => 'required|string|max:150',
            'destino' => 'required|string|max:150|different:origen',
            'ruta_id' => 'nullable|exists:rutas,id',
            'notas'   => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'origen.required'     => 'El punto de partida es obligatorio.',
            'destino.required'    => 'El destino es obligatorio.',
            'destino.different'   => 'El origen y el destino no pueden ser el mismo lugar.',
            'ruta_id.exists'      => 'La ruta seleccionada no existe.',
            'notas.max'           => 'Las notas no pueden exceder 500 caracteres.',
        ];
    }
}
