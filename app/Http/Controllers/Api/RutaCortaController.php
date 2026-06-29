<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GrafoRutas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RutaCortaController extends Controller
{
    public function __invoke(Request $request, GrafoRutas $grafo): JsonResponse
    {
        $validated = $request->validate([
            'origen_id'  => 'required|integer|exists:paraderos,id',
            'destino_id' => 'required|integer|exists:paraderos,id|different:origen_id',
        ]);

        $grafo->construir();
        $resultado = $grafo->rutaMasCorta((int)$validated['origen_id'], (int)$validated['destino_id']);

        if (!$resultado) {
            return response()->json(['error' => 'No se encontró una ruta entre estos paraderos.'], 404);
        }

        $paraderos = array_map(fn($p) => [
            'id'       => $p->id,
            'nombre'   => $p->nombre,
            'latitud'  => (float)$p->latitud,
            'longitud' => (float)$p->longitud,
            'imagen'   => $p->imagen,
            'orden'    => $p->orden,
            'ruta_id'  => $p->ruta_id,
        ], $resultado['paraderos']);

        return response()->json([
            'paraderos'       => $paraderos,
            'distancia_km'    => $resultado['distancia_km'],
            'tiempo_min'      => $resultado['tiempo_min'],
            'nodos_recorridos' => $resultado['nodos_recorridos'],
        ]);
    }
}
