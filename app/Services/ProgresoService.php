<?php

namespace App\Services;

use App\Models\Destino;
use App\Models\LugarVisitado;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ProgresoService
{
    public function __construct(
        protected DestinoService $destinoService
    ) {}

    public function obtenerProgreso(User $user): array
    {
        $total = Destino::where('activo', true)->count();
        $visitados = $user->lugaresVisitados()->with('destino')->get();
        $porcentaje = $total > 0 ? round(($visitados->count() / $total) * 100, 1) : 0;

        return [
            'porcentaje' => $porcentaje,
            'visitados' => $visitados->count(),
            'total' => $total,
            'lugares_visitados' => $visitados,
            'proximo' => $this->destinoService->obtenerProximoNoVisitado($user),
        ];
    }

    public function marcarVisitado(User $user, int $destinoId, array $datos = []): LugarVisitado
    {
        $existe = LugarVisitado::where('user_id', $user->id)
            ->where('destino_id', $destinoId)
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'destino_id' => 'Ya has marcado este destino como visitado.',
            ]);
        }

        $lugar = new LugarVisitado();
        $lugar->user_id = $user->id;
        $lugar->destino_id = $destinoId;
        $lugar->fecha_visita = $datos['fecha_visita'] ?? now()->toDateString();
        $lugar->notas = $datos['notas'] ?? null;
        $lugar->save();

        return $lugar;
    }

    public function desmarcarVisitado(User $user, int $destinoId): bool
    {
        $registro = LugarVisitado::where('user_id', $user->id)
            ->where('destino_id', $destinoId)
            ->first();

        if (!$registro) {
            return false;
        }

        $registro->delete();
        return true;
    }
}
