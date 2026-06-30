<?php

namespace App\Services;

use App\Exceptions\GoWaykiServiceException;
use App\Models\Destino;
use App\Models\LugarVisitado;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgresoService
{
    public function __construct(
        protected DestinoService $destinoService
    ) {}

    public function obtenerProgreso(User $user): array
    {
        try {
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
        } catch (\Throwable $e) {
            Log::error('Error al obtener progreso: ' . $e->getMessage(), ['exception' => $e]);
            throw new GoWaykiServiceException('No pudimos cargar tu progreso. Intenta nuevamente.');
        }
    }

    public function marcarVisitado(User $user, int $destinoId, array $datos = []): LugarVisitado
    {
        // Transacción explícita: esta operación inserta en lugares_visitados,
        // lo que dispara el trigger trg_incrementar_puntaje_exploracion
        // (actualiza user_profiles.puntaje_exploracion) y el trigger
        // trg_incrementar_total_visitas_destino (actualiza destinos.total_visitas).
        // La transacción garantiza atomicidad: si alguna actualización
        // falla, todo se revierte.
        return DB::transaction(function () use ($user, $destinoId, $datos) {
            $existe = LugarVisitado::where('user_id', $user->id)
                ->where('destino_id', $destinoId)
                ->exists();

            if ($existe) {
                throw new GoWaykiServiceException('Ya registraste este destino como visitado anteriormente.');
            }

            $lugar = new LugarVisitado();
            $lugar->user_id = $user->id;
            $lugar->destino_id = $destinoId;
            $lugar->fecha_visita = $datos['fecha_visita'] ?? now()->toDateString();
            $lugar->notas = $datos['notas'] ?? null;
            $lugar->save();

            return $lugar;
        });
    }

    public function desmarcarVisitado(User $user, int $destinoId): bool
    {
        try {
            $registro = LugarVisitado::where('user_id', $user->id)
                ->where('destino_id', $destinoId)
                ->first();

            if (!$registro) {
                return false;
            }

            $registro->delete();
            return true;
        } catch (\Throwable $e) {
            Log::error('Error al desmarcar visitado: ' . $e->getMessage(), ['exception' => $e]);
            throw new GoWaykiServiceException('No pudimos desmarcar tu visita. Intenta nuevamente.');
        }
    }
}
