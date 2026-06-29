<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paradero;
use App\Models\Ruta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminParaderoController extends Controller
{
    public function index(): View
    {
        $paraderos = Paradero::with('ruta')->orderBy('ruta_id')->orderBy('orden')->get();
        return view('admin.paraderos.index', compact('paraderos'));
    }

    public function create(): View
    {
        $rutas = Ruta::where('activa', true)->orderBy('nombre')->get();
        return view('admin.paraderos.create', compact('rutas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ruta_id'   => 'required|exists:rutas,id',
            'nombre'    => 'required|max:150',
            'latitud'   => 'required|numeric',
            'longitud'  => 'required|numeric',
            'orden'     => 'nullable|integer|min:0',
            'referencia'=> 'nullable|max:200',
            'imagen'    => 'nullable|max:255',
        ]);

        if (empty($validated['orden'])) {
            $maxOrden = Paradero::where('ruta_id', $validated['ruta_id'])->max('orden');
            $validated['orden'] = ($maxOrden ?? 0) + 1;
        }

        Paradero::create($validated);

        return redirect()->route('admin.paraderos.index')
            ->with('success', 'Paradero creado correctamente.');
    }

    public function edit(Paradero $paradero): View
    {
        $rutas = Ruta::where('activa', true)->orderBy('nombre')->get();
        return view('admin.paraderos.edit', compact('paradero', 'rutas'));
    }

    public function update(Request $request, Paradero $paradero): RedirectResponse
    {
        $validated = $request->validate([
            'ruta_id'   => 'required|exists:rutas,id',
            'nombre'    => 'required|max:150',
            'latitud'   => 'required|numeric',
            'longitud'  => 'required|numeric',
            'orden'     => 'nullable|integer|min:0',
            'referencia'=> 'nullable|max:200',
            'imagen'    => 'nullable|max:255',
        ]);

        $paradero->update($validated);

        return redirect()->route('admin.paraderos.index')
            ->with('success', 'Paradero actualizado correctamente.');
    }

    public function show(Paradero $paradero): RedirectResponse
    {
        return redirect()->route('admin.paraderos.edit', $paradero);
    }

    public function destroy(Paradero $paradero): RedirectResponse
    {
        $paradero->delete();

        return redirect()->route('admin.paraderos.index')
            ->with('success', 'Paradero eliminado correctamente.');
    }
}
