<?php

namespace App\Http\Controllers;

use App\Http\Requests\LugarVisitadoRequest;
use App\Services\ProgresoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LugarVisitadoController extends Controller
{
    public function __construct(
        protected ProgresoService $progresoService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $progreso = $this->progresoService->obtenerProgreso(auth()->user());

        return view('perfil.progreso', compact('progreso'));
    }

    public function store(LugarVisitadoRequest $request): RedirectResponse
    {
        try {
            $this->progresoService->marcarVisitado(
                auth()->user(),
                $request->destino_id,
                $request->all()
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return back()->with('success', 'Destino marcado como visitado.');
    }

    public function destroy(int $destinoId): RedirectResponse
    {
        $eliminado = $this->progresoService->desmarcarVisitado(auth()->user(), $destinoId);

        if (!$eliminado) {
            return back()->with('error', 'No se encontró el registro de visita.');
        }

        return back()->with('success', 'Destino desmarcado correctamente.');
    }
}
