<?php

namespace App\Services;

use App\Models\Destino;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class DestinoService
{
    public function obtenerTodos(array $filtros = []): Collection
    {
        return Destino::where('activo', true)
            ->when($filtros['q'] ?? null, fn($q, $term) =>
                $q->where('nombre', 'like', "%{$term}%"))
            ->when($filtros['categoria'] ?? null, fn($q, $cat) =>
                $q->where('categoria', $cat))
            ->when($filtros['distrito'] ?? null, fn($q, $dist) =>
                $q->where('distrito', $dist))
            ->orderBy('calificacion', 'desc')
            ->get();
    }

    public function obtenerPaginados(array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        return Destino::where('activo', true)
            ->when($filtros['q'] ?? null, fn($q, $term) =>
                $q->where(function ($sub) use ($term) {
                    $sub->where('nombre', 'like', "%{$term}%")
                        ->orWhere('descripcion', 'like', "%{$term}%");
                }))
            ->when($filtros['categoria'] ?? null, fn($q, $cat) =>
                $q->where('categoria', $cat))
            ->when($filtros['distrito'] ?? null, fn($q, $dist) =>
                $q->where('distrito', $dist))
            ->orderBy('calificacion', 'desc')
            ->paginate($perPage)
            ->withQueryString();
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
