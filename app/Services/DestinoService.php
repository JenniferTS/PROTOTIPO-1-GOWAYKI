<?php

namespace App\Services;

use App\Models\Destino;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DestinoService
{
    public function obtenerTodos(array $filtros = []): Collection
    {
        $query = Destino::where('activo', true);

        if (!empty($filtros['categoria'])) {
            $query->where('categoria', $filtros['categoria']);
        }

        if (!empty($filtros['distrito'])) {
            $query->where('distrito', $filtros['distrito']);
        }

        if (!empty($filtros['q'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('nombre', 'like', "%{$filtros['q']}%")
                    ->orWhere('descripcion', 'like', "%{$filtros['q']}%");
            });
        }

        return $query->orderBy('calificacion', 'desc')->get();
    }

    public function obtenerPaginados(array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Destino::where('activo', true);

        if (!empty($filtros['categoria'])) {
            $query->where('categoria', $filtros['categoria']);
        }

        if (!empty($filtros['distrito'])) {
            $query->where('distrito', $filtros['distrito']);
        }

        if (!empty($filtros['q'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('nombre', 'like', "%{$filtros['q']}%")
                    ->orWhere('descripcion', 'like', "%{$filtros['q']}%");
            });
        }

        return $query->orderBy('calificacion', 'desc')->paginate($perPage);
    }

    public function obtenerPorId(int $id): Destino
    {
        return Destino::findOrFail($id);
    }

    public function obtenerProximoNoVisitado(User $user): ?Destino
    {
        $visitados = $user->lugaresVisitados()->pluck('destino_id')->toArray();

        return Destino::where('activo', true)
            ->whereNotIn('id', $visitados)
            ->orderBy('calificacion', 'desc')
            ->first();
    }
}
