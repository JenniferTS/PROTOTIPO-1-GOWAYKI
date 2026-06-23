<?php

namespace App\Http\Controllers;

use App\Exceptions\GoWaykiServiceException;
use App\Http\Requests\LugarVisitadoRequest;
use App\Services\ProgresoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LugarVisitadoController extends Controller
{
    public function __construct(
        protected ProgresoService $progresoService
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        try {
            $progreso = $this->progresoService->obtenerProgreso(auth()->user());
        } catch (\Throwable $e) {
            Log::error('Error cargando progreso: ' . $e->getMessage(), ['exception' => $e]);
            return view('perfil.progreso', [
                'progreso' => null,
                'errorCarga' => true,
            ]);
        }

        return view('perfil.progreso', [
            'progreso' => $progreso,
            'errorCarga' => false,
        ]);
    }

    public function store(LugarVisitadoRequest $request): RedirectResponse
    {
        try {
            $destino = \App\Models\Destino::findOrFail($request->destino_id);
            $this->progresoService->marcarVisitado(
                auth()->user(),
                $request->destino_id,
                $request->validated()
            );
        } catch (GoWaykiServiceException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'El destino seleccionado no existe.');
        } catch (\Throwable $e) {
            Log::error('Error al marcar destino como visitado: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'No pudimos guardar tu visita. Intenta nuevamente.')->withInput();
        }

        $nombre = $destino->nombre ?? 'Destino';
        return back()->with('success', "¡{$nombre} marcado como visitado!");
    }

    public function destroy(int $destinoId): RedirectResponse
    {
        try {
            $eliminado = $this->progresoService->desmarcarVisitado(auth()->user(), $destinoId);
        } catch (GoWaykiServiceException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Error al desmarcar visitado: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'No pudimos procesar tu solicitud. Intenta nuevamente.');
        }

        if (!$eliminado) {
            return back()->with('error', 'No se encontró el registro de visita.');
        }

        return back()->with('success', 'Destino desmarcado correctamente.');
    }
}
