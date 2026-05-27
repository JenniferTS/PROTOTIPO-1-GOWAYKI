<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Services\DestinoService;
use App\Services\RutaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DestinoController extends Controller
{
    public function __construct(
        protected DestinoService $destinoService
    ) {}

    public function index(Request $request): View
    {
        $filtros = $request->only(['categoria', 'distrito', 'q']);
        $destinos = $this->destinoService->obtenerPaginados($filtros, 12);
        $categorias = ['turistico', 'cultural', 'gastronomico', 'recreativo', 'historico'];
        $distritos = Destino::where('activo', true)->distinct()->pluck('distrito');

        return view('destinos.index', compact('destinos', 'filtros', 'categorias', 'distritos'));
    }

    public function show(int $id): View
    {
        try {
            $destino = $this->destinoService->obtenerPorId($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Destino no encontrado');
        }

        $visitado = auth()->check() ? $destino->visitadoPor(auth()->user()) : false;

        return view('destinos.show', compact('destino', 'visitado'));
    }
}
