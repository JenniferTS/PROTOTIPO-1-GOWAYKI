<?php

namespace App\Http\Controllers;

use App\Models\Recorrido;
use App\Services\RecorridoService;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            $resultado = $this->recorridoService->planificar($request->origen, $request->destino);
        }

        $origenes = $this->rutaService->listarOrigenes();
        $destinos = $this->rutaService->listarDestinos();

        return view('recorridos.planificar', compact('resultado', 'request', 'origenes', 'destinos'));
    }

    public function guardar(Request $request): RedirectResponse
    {
        $request->validate([
            'origen'  => 'required|string',
            'destino' => 'required|string',
            'ruta_id' => 'nullable|exists:rutas,id',
            'notas'   => 'nullable|string|max:500',
        ]);

        $recorrido = $this->recorridoService->guardar($request->all(), auth()->user());

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
