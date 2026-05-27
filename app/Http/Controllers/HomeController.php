<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Services\DestinoService;
use App\Services\ProgresoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected DestinoService $destinoService,
        protected ProgresoService $progresoService
    ) {}

    public function index(Request $request): View
    {
        $sliderDestinos = Destino::where('activo', true)
            ->orderBy('id')
            ->take(5)
            ->get();

        $destinos = Destino::where('activo', true)
            ->orderBy('calificacion', 'desc')
            ->take(4)
            ->get();

        $progreso = null;
        if (auth()->check()) {
            $progreso = $this->progresoService->obtenerProgreso(auth()->user());
        }

        return view('home.index', compact('sliderDestinos', 'destinos', 'progreso'));
    }
}
