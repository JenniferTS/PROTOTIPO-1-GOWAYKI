<?php

namespace App\Http\Controllers;

use App\Exceptions\GoWaykiServiceException;
use App\Http\Requests\RutaBusquedaRequest;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RutaController extends Controller
{
    public function __construct(
        protected RutaService $rutaService
    ) {}

    public function index(Request $request): View
    {
        if ($request->has('origen') || $request->has('destino')) {
            $validated = $request->validate([
                'origen'  => 'required|string|max:150',
                'destino' => 'required|string|max:150|different:origen',
            ]);

            $resultado = $this->rutaService->buscarRutas($validated['origen'], $validated['destino']);
            $rutas = $resultado['rutas'];
            $degradado = $resultado['degradado'];
        } else {
            $resultado = $this->rutaService->obtenerTodasActivas();
            $rutas = $resultado['rutas'];
            $degradado = $resultado['degradado'];
        }

        return view('rutas.index', compact('rutas', 'request', 'degradado'));
    }

    public function show(int $id): View
    {
        try {
            $ruta = $this->rutaService->obtenerRutaConParaderos($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404);
        } catch (\Throwable $e) {
            Log::error('Error cargando detalle de ruta: ' . $e->getMessage(), ['exception' => $e]);
            return view('rutas.error', [
                'mensaje' => 'No pudimos cargar el detalle de esta ruta.',
            ]);
        }

        return view('rutas.show', compact('ruta'));
    }
}
