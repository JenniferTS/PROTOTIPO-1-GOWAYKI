@extends('admin.layouts.master')

@section('title', 'Dashboard')

@push('styles')
<style>
.chart-container { position: relative; height: 260px; width: 100%; }
.chart-col { min-width: 0; }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-valor">{{ $totalRutas }}</div>
        <div class="stat-label">Rutas activas</div>
        <div class="progress-bar"><div class="fill" style="width:{{ min(100, $totalRutas * 10) }}%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-valor">{{ $totalParaderos }}</div>
        <div class="stat-label">Paraderos registrados</div>
        <div class="progress-bar"><div class="fill" style="width:{{ min(100, $totalParaderos * 2) }}%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-valor">{{ $totalUsuarios }}</div>
        <div class="stat-label">Usuarios totales</div>
        <div class="progress-bar"><div class="fill" style="width:{{ min(100, $totalUsuarios * 5) }}%"></div></div>
    </div>
    <div class="stat-card kpi-card--highlight">
        <div class="stat-valor">+{{ $usuariosSemana }}</div>
        <div class="stat-label">Nuevos usuarios (7 días)</div>
        <div class="progress-bar" style="background:rgba(255,255,255,0.25)"><div class="fill" style="width:{{ min(100, $usuariosSemana * 20) }}%;background:#fff"></div></div>
    </div>
</div>

{{-- GRÁFICOS: fila superior --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    <div class="card chart-col" style="padding:18px;">
        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">📈 Registros por día</h3>
        <div class="chart-container">
            <canvas id="chartRegistros"></canvas>
        </div>
    </div>
    <div class="card chart-col" style="padding:18px;">
        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">🗺️ Recorridos por día</h3>
        <div class="chart-container">
            <canvas id="chartRecorridos"></canvas>
        </div>
    </div>
</div>

{{-- GRÁFICO: fila inferior + tablas --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;">
    <div class="card chart-col" style="padding:18px;">
        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">🥧 Paraderos por ruta</h3>
        <div class="chart-container">
            <canvas id="chartParaderos"></canvas>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card" style="padding:18px;flex:1;">
            <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">🚌 Rutas con más paraderos</h3>
            @if ($rutasMasParaderos->count() > 0)
            <table style="width:100%;font-size:0.85rem;">
                <thead><tr><th style="text-align:left;padding-bottom:6px;color:var(--color-text-muted);">Ruta</th><th style="text-align:right;padding-bottom:6px;color:var(--color-text-muted);">Paraderos</th></tr></thead>
                <tbody>
                @foreach ($rutasMasParaderos as $r)
                    <tr><td style="padding:4px 0;">{{ $r->nombre }}</td><td style="text-align:right;font-weight:700;">{{ $r->paraderos_count }}</td></tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state"><p>Aún no hay rutas con paraderos.</p></div>
            @endif
        </div>
        <div class="card" style="padding:18px;flex:1;">
            <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">⭐ Destinos favoritos</h3>
            @if ($topDestinos->count() > 0)
            <table style="width:100%;font-size:0.85rem;">
                <thead><tr><th style="text-align:left;padding-bottom:6px;color:var(--color-text-muted);">Destino</th><th style="text-align:right;padding-bottom:6px;color:var(--color-text-muted);">Visitas</th></tr></thead>
                <tbody>
                @foreach ($topDestinos as $d)
                    <tr><td style="padding:4px 0;">{{ $d->nombre }}</td><td style="text-align:right;font-weight:700;">{{ $d->lugares_visitados_count }}</td></tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state"><p>Aún no hay visitas registradas.</p></div>
            @endif
        </div>
    </div>
</div>

<div class="card" style="padding:18px;">
    <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:12px;">📊 Resumen general</h3>
    <table style="width:100%;font-size:0.85rem;">
        <tbody>
            <tr><td style="padding:6px 0;">Administradores</td><td style="text-align:right;font-weight:700;">{{ $totalAdmins }}</td></tr>
            <tr><td style="padding:6px 0;">Recorridos guardados</td><td style="text-align:right;font-weight:700;">{{ $totalRecorridos }}</td></tr>
            <tr><td style="padding:6px 0;">Destinos turísticos</td><td style="text-align:right;font-weight:700;">{{ $totalDestinos }}</td></tr>
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";

    // 1. Barras — Registros por día
    new Chart(document.getElementById('chartRegistros'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($fechas) !!},
            datasets: [{
                label: 'Registros',
                data: {!! json_encode($valoresRegistros) !!},
                backgroundColor: '#004E72',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Barras — Recorridos por día
    new Chart(document.getElementById('chartRecorridos'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($fechas) !!},
            datasets: [{
                label: 'Recorridos',
                data: {!! json_encode($valoresRecorridos) !!},
                backgroundColor: '#FF6E42',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. Doughnut — Paraderos por ruta
    var labelsParaderos = {!! json_encode($paraderosPorRuta->pluck('nombre')) !!};
    var dataParaderos   = {!! json_encode($paraderosPorRuta->pluck('paraderos_count')) !!};

    if (labelsParaderos.length > 0) {
        var colores = ['#004E72','#FF6E42','#22C55E','#EF4444','#8B5CF6','#F59E0B','#06B6D4','#EC4899'];
        new Chart(document.getElementById('chartParaderos'), {
            type: 'doughnut',
            data: {
                labels: labelsParaderos,
                datasets: [{
                    data: dataParaderos,
                    backgroundColor: colores.slice(0, labelsParaderos.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 8, font: { size: 11 } }
                    }
                }
            }
        });
    }
});
</script>
@endpush
