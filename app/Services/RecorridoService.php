<?php

namespace App\Services;

use App\Models\Recorrido;
use App\Models\User;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Collection;

class RecorridoService
{
    public function __construct(
        protected RutaService $rutaService
    ) {}

    public function planificar(string $origen, string $destino): array
    {
        $rutas = $this->rutaService->buscarRutas($origen, $destino);

        return [
            'rutas' => $rutas,
            'origen' => $origen,
            'destino' => $destino,
            'total_opciones' => $rutas->count(),
        ];
    }

    public function guardar(array $datos, User $user): Recorrido
    {
        $recorrido = new Recorrido();
        $recorrido->user_id = $user->id;
        $recorrido->nombre = $datos['origen'] . ' → ' . $datos['destino'];
        $recorrido->origen = $datos['origen'];
        $recorrido->destino = $datos['destino'];
        $recorrido->ruta_id = $datos['ruta_id'] ?? null;
        $recorrido->notas = $datos['notas'] ?? null;
        $recorrido->save();

        return $recorrido;
    }
}
