<?php

namespace App\Http\Controllers;

use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function __construct(
        protected RutaService $rutaService
    ) {}

    public function index(Request $request): View
    {
        if ($request->has('origen') && $request->has('destino')) {
            $rutas = $this->rutaService->buscarRutas($request->origen, $request->destino);
        } else {
            $rutas = $this->rutaService->obtenerTodasActivas();
        }

        return view('rutas.index', compact('rutas', 'request'));
    }

    public function show(int $id): View
    {
        try {
            $ruta = $this->rutaService->obtenerRutaConParaderos($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Ruta no encontrada');
        }

        return view('rutas.show', compact('ruta'));
    }
}
