<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RutaRequest;
use App\Models\Ruta;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RutaAdminController extends Controller
{
    public function __construct(
        protected RutaService $rutaService
    ) {}

    public function index(): View
    {
        $rutas = Ruta::with('paraderos')->get();
        return view('admin.rutas.index', compact('rutas'));
    }

    public function create(): View
    {
        return view('admin.rutas.create');
    }

    public function store(RutaRequest $request): RedirectResponse
    {
        Ruta::create($request->validated());

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta creada correctamente.');
    }

    public function show(Ruta $ruta): RedirectResponse
    {
        return redirect()->route('admin.rutas.edit', $ruta);
    }

    public function edit(Ruta $ruta): View
    {
        return view('admin.rutas.edit', compact('ruta'));
    }

    public function update(RutaRequest $request, Ruta $ruta): RedirectResponse
    {
        // Establecer variable de sesión para el trigger de auditoría
        // trg_auditoria_rutas_update capturará el usuario que modificó
        DB::statement('SET @current_user_id = ?', [Auth::id()]);

        $ruta->update($request->validated());

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Ruta $ruta): RedirectResponse
    {
        // Establecer variable de sesión para el trigger de auditoría
        // trg_auditoria_rutas_delete capturará el usuario que eliminó
        DB::statement('SET @current_user_id = ?', [Auth::id()]);

        $ruta->delete();

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
