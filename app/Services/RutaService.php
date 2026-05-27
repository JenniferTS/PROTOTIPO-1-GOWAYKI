<?php

namespace App\Services;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RutaService
{
    public function buscarRutas(string $origen, string $destino): Collection
    {
        return Ruta::where('activa', true)
            ->where(function ($query) use ($origen) {
                $query->where('origen', 'like', "%{$origen}%")
                    ->orWhere('destino', 'like', "%{$origen}%");
            })
            ->where(function ($query) use ($destino) {
                $query->where('origen', 'like', "%{$destino}%")
                    ->orWhere('destino', 'like', "%{$destino}%");
            })
            ->with('paraderos')
            ->get();
    }

    public function obtenerTodasActivas(): Collection
    {
        return Ruta::where('activa', true)
            ->with('paraderos')
            ->get();
    }

    public function obtenerRutaConParaderos(int $id): Ruta
    {
        return Ruta::with('paraderos')->findOrFail($id);
    }

    public function listarOrigenes(): array
    {
        return Ruta::where('activa', true)
            ->distinct()
            ->pluck('origen')
            ->toArray();
    }

    public function listarDestinos(): array
    {
        return Ruta::where('activa', true)
            ->distinct()
            ->pluck('destino')
            ->toArray();
    }
}
