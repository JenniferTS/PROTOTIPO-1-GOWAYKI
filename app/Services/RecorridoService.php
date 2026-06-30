<?php

namespace App\Services;

use App\Models\Recorrido;
use App\Models\User;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RecorridoService
{
    public function __construct(
        protected RutaService $rutaService
    ) {}

    public function planificar(string $origen, string $destino): array
    {
        $resultado = $this->rutaService->buscarRutas($origen, $destino);

        return [
            'rutas' => $resultado['rutas'],
            'origen' => $origen,
            'destino' => $destino,
            'total_opciones' => $resultado['rutas']->count(),
            'degradado' => $resultado['degradado'],
        ];
    }

    public function guardar(array $datos, User $user): Recorrido
    {
        // Transacción explícita: crear un recorrido implica escribir en
        // la tabla recorridos. Si en el futuro se agregan operaciones
        // dependientes (ej. notificaciones, actualización de contadores),
        // la transacción garantiza atomicidad. Sin ella, si la segunda
        // operación falla, quedaría un recorrido huérfano.
        return DB::transaction(function () use ($datos, $user) {
            $recorrido = new Recorrido();
            $recorrido->user_id = $user->id;
            $recorrido->nombre = $datos['origen'] . ' → ' . $datos['destino'];
            $recorrido->origen = $datos['origen'];
            $recorrido->destino = $datos['destino'];
            $recorrido->ruta_id = $datos['ruta_id'] ?? null;
            $recorrido->notas = $datos['notas'] ?? null;
            $recorrido->save();

            return $recorrido;
        });
    }
}
