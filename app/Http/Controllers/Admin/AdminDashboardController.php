<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destino;
use App\Models\Paradero;
use App\Models\Recorrido;
use App\Models\Ruta;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalRutas      = Ruta::count();
        $totalParaderos  = Paradero::count();
        $totalUsuarios   = User::count();
        $totalAdmins     = User::where('role', 'admin')->count();
        $totalRecorridos = Recorrido::count();
        $totalDestinos   = Destino::where('activo', true)->count();
        $usuariosSemana  = User::where('created_at', '>=', now()->subWeek())->count();

        $topDestinos = Destino::where('activo', true)
            ->withCount('lugaresVisitados')
            ->orderBy('lugares_visitados_count', 'desc')
            ->take(5)
            ->get();

        $rutasMasParaderos = Ruta::withCount('paraderos')
            ->orderBy('paraderos_count', 'desc')
            ->take(5)
            ->get();

        // Registros por día (últimos 7 días)
        $registrosDiarios = User::where('created_at', '>=', now()->subDays(6))
            ->select(DB::raw("DATE(created_at) as fecha"), DB::raw("COUNT(*) as total"))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->pluck('total', 'fecha')
            ->toArray();

        $fechas = collect();
        $valoresRegistros = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dia = now()->subDays($i)->format('Y-m-d');
            $fechas->push(now()->subDays($i)->format('d/m'));
            $valoresRegistros->push($registrosDiarios[$dia] ?? 0);
        }

        // Recorridos por día (últimos 7 días)
        $recorridosDiarios = Recorrido::where('created_at', '>=', now()->subDays(6))
            ->select(DB::raw("DATE(created_at) as fecha"), DB::raw("COUNT(*) as total"))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->pluck('total', 'fecha')
            ->toArray();

        $valoresRecorridos = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dia = now()->subDays($i)->format('Y-m-d');
            $valoresRecorridos->push($recorridosDiarios[$dia] ?? 0);
        }

        // Distribución de paraderos por ruta (top 8 para gráfico)
        $paraderosPorRuta = Ruta::withCount('paraderos')
            ->orderBy('paraderos_count', 'desc')
            ->take(8)
            ->get()
            ->filter(fn($r) => $r->paraderos_count > 0)
            ->values();

        return view('admin.dashboard.index', compact(
            'totalRutas', 'totalParaderos', 'totalUsuarios', 'totalAdmins',
            'totalRecorridos', 'totalDestinos', 'usuariosSemana',
            'topDestinos', 'rutasMasParaderos',
            'fechas', 'valoresRegistros', 'valoresRecorridos', 'paraderosPorRuta'
        ));
    }
}
