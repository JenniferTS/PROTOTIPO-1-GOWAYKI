<?php

namespace App\Services;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class RutaService
{
    public function buscarRutas(string $origen, string $destino): array
    {
        try {
            $rutas = Ruta::where('activa', true)
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

            return [
                'rutas' => $rutas,
                'degradado' => false,
            ];
        } catch (\Throwable $e) {
            Log::error('Error al buscar rutas: ' . $e->getMessage(), ['exception' => $e]);

            $rutas = Ruta::where('activa', true)->with('paraderos')->get();

            return [
                'rutas' => $rutas,
                'degradado' => true,
            ];
        }
    }

    public function obtenerTodasActivas(): array
    {
        try {
            $rutas = Ruta::where('activa', true)
                ->with('paraderos')
                ->get();

            return [
                'rutas' => $rutas,
                'degradado' => false,
            ];
        } catch (\Throwable $e) {
            Log::error('Error al obtener rutas activas: ' . $e->getMessage(), ['exception' => $e]);

            return [
                'rutas' => new Collection(),
                'degradado' => true,
            ];
        }
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
