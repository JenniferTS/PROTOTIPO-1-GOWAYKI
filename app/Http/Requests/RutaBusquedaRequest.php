<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RutaBusquedaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origen'  => 'required|string|max:150',
            'destino' => 'required|string|max:150|different:origen',
        ];
    }

    public function messages(): array
    {
        return [
            'origen.required'     => 'Indica el punto de partida.',
            'destino.required'    => 'Indica el destino.',
            'destino.different'   => 'El origen y el destino no pueden ser el mismo lugar.',
        ];
    }
}
