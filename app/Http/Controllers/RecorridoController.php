<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecorridoRequest;
use App\Models\Recorrido;
use App\Services\RecorridoService;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RecorridoController extends Controller
{
    public function __construct(
        protected RecorridoService $recorridoService,
        protected RutaService $rutaService
    ) {}

    public function planificar(Request $request): View
    {
        $resultado = null;

        if ($request->filled('origen') && $request->filled('destino')) {
            $request->validate([
                'origen'  => 'required|string|max:150',
                'destino' => 'required|string|max:150|different:origen',
            ]);

            $resultado = $this->recorridoService->planificar($request->origen, $request->destino);
        }

        $todosParaderos = \App\Models\Paradero::orderBy('ruta_id')->orderBy('orden')
            ->select('id', 'nombre', 'ruta_id', 'latitud', 'longitud', 'imagen', 'imagen_url')
            ->get();

        return view('recorridos.planificar', compact('resultado', 'request', 'todosParaderos'));
    }

    public function guardar(RecorridoRequest $request): RedirectResponse
    {
        try {
            $recorrido = $this->recorridoService->guardar($request->validated(), auth()->user());
        } catch (\Throwable $e) {
            Log::error('Error al guardar recorrido: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'No pudimos guardar tu recorrido. Intenta nuevamente.')->withInput();
        }

        return redirect()
            ->route('recorridos.miRuta')
            ->with('success', 'Recorrido guardado correctamente.');
    }

    public function miRuta(): View
    {
        $recorridos = auth()->user()
            ->recorridos()
            ->with('ruta')
            ->latest()
            ->get();

        return view('recorridos.mi-ruta', compact('recorridos'));
    }

    public function destroy(Recorrido $recorrido): RedirectResponse
    {
        if ($recorrido->user_id !== auth()->id()) {
            abort(403);
        }

        $recorrido->delete();

        return redirect()
            ->route('recorridos.miRuta')
            ->with('success', 'Recorrido eliminado correctamente.');
    }
}
