<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RutaRequest;
use App\Models\Ruta;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function edit(Ruta $ruta): View
    {
        return view('admin.rutas.edit', compact('ruta'));
    }

    public function update(RutaRequest $request, Ruta $ruta): RedirectResponse
    {
        $ruta->update($request->validated());

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(Ruta $ruta): RedirectResponse
    {
        $ruta->delete();

        return redirect()
            ->route('admin.rutas.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }
}
