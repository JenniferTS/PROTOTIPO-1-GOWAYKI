@extends('layouts.app')

@section('title', ($ruta->nombre ?? 'Ruta') . ' — GoWayki')

@push('styles')
<style>
html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
body > footer { display: none; }
body > main { padding: 0; margin: 0; height: 100vh; }

body > nav.navbar-gowayki {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    transform: translateY(-100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1000;
    box-shadow: 0 2px 12px rgba(0,0,0,0.15);
}

body > nav.navbar-gowayki.visible {
    transform: translateY(0);
}

#navbar-trigger {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 25px;
    z-index: 1001;
}

#mapa-ruta {
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 0;
}

.ruta-sidebar {
    position: fixed;
    top: 0; left: 0;
    width: 380px;
    height: 100vh;
    background: #fff;
    z-index: 500;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 16px rgba(0,0,0,0.12);
}

.ruta-sidebar-header {
    padding: 24px 20px 16px;
    border-bottom: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.ruta-sidebar-header .ruta-nombre {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 10px;
}

.ruta-sidebar-header .ruta-nombre .color-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ruta-sidebar-header .ruta-descripcion {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 6px;
}

.ruta-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-top: 14px;
}

.ruta-stat {
    background: #f9fafb;
    border-radius: 10px;
    padding: 10px 8px;
    text-align: center;
}

.ruta-stat-valor {
    font-size: 1rem;
    font-weight: 700;
    color: #F83A34;
}

.ruta-stat-label {
    font-size: 0.7rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruta-trayecto {
    margin-top: 12px;
    padding: 10px 14px;
    background: #fff5f5;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.9rem;
}

.ruta-trayecto .origen {
    font-weight: 600;
    color: #10b981;
}

.ruta-trayecto .destino {
    font-weight: 600;
    color: #ef4444;
}

.ruta-trayecto .flecha {
    color: #d1d5db;
    flex-shrink: 0;
}

.ruta-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 12px 20px 24px;
}

.ruta-sidebar-body::-webkit-scrollbar {
    width: 6px;
}
.ruta-sidebar-body::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.paradero-item {
    display: flex;
    align-items: stretch;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background 0.15s;
}

.paradero-item:last-child { border-bottom: none; }
.paradero-item:hover { background: #f9fafb; border-radius: 8px; padding-left: 6px; padding-right: 6px; }

.paradero-numero {
    width: 28px;
    height: 28px;
    min-width: 28px;
    background: #FADBD8;
    color: #F83A34;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.8rem;
    margin-top: 4px;
}

.paradero-contenido {
    flex: 1;
    min-width: 0;
}

.paradero-contenido .paradero-nombre {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.9rem;
}

.paradero-contenido .paradero-info {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 2px;
}

.paradero-foto {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.paradero-item .linea-conexion {
    width: 2px;
    min-height: 24px;
    background: #e5e7eb;
    margin-left: 13px;
    margin-top: 4px;
    margin-bottom: 4px;
}

/* Bubble icon */
.gw-bubble-icon {
    width: 44px;
    height: 44px;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    border: 3px solid #F83A34;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    transition: transform 0.2s;
}

.gw-bubble-icon:hover {
    transform: rotate(-45deg) scale(1.15);
}

.gw-bubble-icon img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    transform: rotate(45deg);
}

.leaflet-control-zoom {
    margin-left: 400px !important;
}

/* Route alternatives panel */
#ruta-alternatives {
    position: fixed;
    top: 60px;
    left: 400px;
    z-index: 600;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    padding: 12px 16px;
    max-width: 340px;
    min-width: 280px;
}

.ruta-alt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ruta-alt-count {
    background: #F83A34;
    color: #fff;
    font-size: 0.65rem;
    padding: 2px 8px;
    border-radius: 10px;
}

.ruta-alt-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.ruta-alt-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.15s;
    font-size: 0.85rem;
}

.ruta-alt-item.active {
    border-color: #F83A34;
    background: #FFF5F5;
}

.ruta-alt-item:not(.active):hover {
    background: #F9FAFB;
}

.ruta-alt-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ruta-alt-info {
    flex: 1;
}

.ruta-alt-info .ruta-alt-label {
    font-weight: 600;
    color: #1F2937;
}

.ruta-alt-info .ruta-alt-detail {
    font-size: 0.75rem;
    color: #6B7280;
}

.ruta-alt-badge {
    background: #10B981;
    color: #fff;
    font-size: 0.6rem;
    padding: 2px 6px;
    border-radius: 6px;
    white-space: nowrap;
    text-transform: uppercase;
    font-weight: 700;
}

.paradero-tiempo {
    font-size: 0.7rem;
    color: #10B981;
    font-weight: 600;
    margin-top: 1px;
}

/* -- Planificador: inputs en sidebar -- */
.rp-inputs {
    margin-bottom: 12px;
}

.rp-input-row {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.rp-input-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #6b7280;
    min-width: 44px;
}

.rp-autocomplete {
    position: relative;
    flex: 1;
}

.rp-input {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.78rem;
    color: #1f2937;
    background: #fff;
    box-sizing: border-box;
}

.rp-input:focus {
    outline: none;
    border-color: #F83A34;
    box-shadow: 0 0 0 2px rgba(248,58,52,0.12);
}

.rp-input::placeholder {
    color: #9ca3af;
    font-size: 0.72rem;
}

.rp-suggestions {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    max-height: 180px;
    overflow-y: auto;
    z-index: 700;
}

.rp-suggestions.open {
    display: block;
}

.rp-suggestion-item {
    padding: 6px 10px;
    font-size: 0.78rem;
    color: #374151;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
}

.rp-suggestion-item:last-child {
    border-bottom: none;
}

.rp-suggestion-item:hover,
.rp-suggestion-item.active {
    background: #fef2f2;
    color: #F83A34;
}

.rp-suggestion-item .rp-sug-label {
    font-weight: 600;
}

.rp-suggestion-item .rp-sug-ruta {
    font-size: 0.65rem;
    color: #9ca3af;
    margin-left: 4px;
}

/* -- Resultado flotante: solo info con imágenes -- */
.rp-resultado-panel {
    position: fixed;
    top: 50px;
    left: 400px;
    z-index: 600;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    padding: 14px 18px;
    max-width: 360px;
    min-width: 280px;
}

.rp-resultado-panel.rp-ok {
    border-left: 4px solid #10B981;
}

.rp-resultado-panel.rp-error {
    border-left: 4px solid #dc2626;
    color: #dc2626;
    font-size: 0.8rem;
}

.rp-resultado-panel .rp-resumen {
    font-weight: 700;
    color: #1f2937;
    font-size: 0.85rem;
    margin-bottom: 6px;
}

.rp-resultado-panel .rp-imagenes {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.rp-resultado-panel .rp-img-cuadro {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    flex-shrink: 0;
}

.rp-resultado-panel .rp-img-flecha {
    color: #9ca3af;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.rp-resultado-panel .rp-path {
    color: #374151;
    line-height: 1.5;
    font-size: 0.72rem;
    max-height: 80px;
    overflow-y: auto;
}

.rp-resultado-panel .rp-path .step {
    display: inline-block;
    background: #dcfce7;
    padding: 1px 6px;
    border-radius: 4px;
    margin: 1px 0;
    color: #16a34a;
}

    @media (max-width: 768px) {
        .rp-resultado-panel {
            left: 12px;
            top: 45px;
            max-width: calc(100vw - 24px);
            min-width: unset;
        }
    }

    .rp-buscar-otra {
        width: 100%;
        padding: 7px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #F83A34;
        background: #fff;
        border: 1.5px solid #F83A34;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
        margin-top: 6px;
    }

    .rp-buscar-otra:hover {
        background: #F83A34;
        color: #fff;
    }

@media (max-width: 768px) {
    .ruta-sidebar {
        width: 100%;
        height: 45vh;
        top: auto;
        bottom: 0;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
        border-radius: 16px 16px 0 0;
    }

    .ruta-sidebar-header {
        padding: 16px 16px 12px;
    }

    .ruta-sidebar-body {
        padding: 8px 16px 16px;
    }

    .leaflet-control-zoom {
        margin-left: 12px !important;
        margin-bottom: 46vh !important;
    }

    .ruta-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
    }

    #ruta-alternatives {
        left: 12px;
        top: 50px;
        max-width: calc(100vw - 24px);
        min-width: unset;
    }

    .ruta-alt-item {
        font-size: 0.8rem;
    }
}
</style>
@endpush

@section('content')
<div id="mapa-ruta"></div>

<div id="navbar-trigger"></div>

<div id="ruta-alternatives" style="display:none;">
    <div class="ruta-alt-header">
        <span>Rutas sugeridas</span>
        <span class="ruta-alt-count" id="alt-count"></span>
    </div>
    <div id="alt-list" class="ruta-alt-list"></div>
</div>

<aside class="ruta-sidebar">
    <div class="ruta-sidebar-header">
        <div class="rp-inputs">
            <div class="rp-input-row">
                <span class="rp-input-label">Origen</span>
                <div class="rp-autocomplete">
                    <input type="text" id="rp-origen-input" class="rp-input" placeholder="Escribe el paradero..." autocomplete="off">
                    <div id="rp-origen-suggestions" class="rp-suggestions"></div>
                </div>
            </div>
            <div class="rp-input-row">
                <span class="rp-input-label">Destino</span>
                <div class="rp-autocomplete">
                    <input type="text" id="rp-destino-input" class="rp-input" placeholder="Escribe el paradero..." autocomplete="off">
                    <div id="rp-destino-suggestions" class="rp-suggestions"></div>
                </div>
            </div>
        </div>

        <button id="rp-buscar-otra" class="rp-buscar-otra" style="display:none;">Buscar otra ruta</button>

        <div class="ruta-nombre">
            <span class="color-dot" style="background:{{ $ruta->color_linea ?? '#F83A34' }}"></span>
            {{ $ruta->nombre }}
        </div>
        @if ($ruta->descripcion)
            <p class="ruta-descripcion">{{ $ruta->descripcion }}</p>
        @endif

        <div class="ruta-stats">
            <div class="ruta-stat">
                <div class="ruta-stat-valor">{{ $ruta->tiempo_estimado_minutos ?? '—' }}</div>
                <div class="ruta-stat-label">Minutos</div>
            </div>
            <div class="ruta-stat">
                <div class="ruta-stat-valor">{{ $ruta->costo_formateado ?? 'S/ 0.00' }}</div>
                <div class="ruta-stat-label">Tarifa</div>
            </div>
            <div class="ruta-stat">
                <div class="ruta-stat-valor">{{ $ruta->paraderos->count() }}</div>
                <div class="ruta-stat-label">Paraderos</div>
            </div>
        </div>

        @if ($ruta->origen && $ruta->destino)
        <div class="ruta-trayecto">
            <span class="origen">{{ $ruta->origen }}</span>
            <svg class="flecha" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            <span class="destino">{{ $ruta->destino }}</span>
        </div>
        @endif
    </div>

    <div class="ruta-sidebar-body">
        @if ($ruta->paraderos->isEmpty())
            <p style="color:#9ca3af;font-style:italic;text-align:center;padding:40px 0;">
                Aún no se han registrado paraderos para esta ruta.
            </p>
        @else
            @foreach ($ruta->paraderos as $paradero)
                <div class="paradero-item" data-lat="{{ $paradero->latitud }}" data-lng="{{ $paradero->longitud }}" data-idx="{{ $loop->index }}">
                    <div style="display:flex;flex-direction:column;align-items:center;">
                        <div class="paradero-numero">{{ $paradero->orden }}</div>
                        @if (!$loop->last)
                            <div class="linea-conexion"></div>
                        @endif
                    </div>
                    <div class="paradero-contenido">
                        <div class="paradero-nombre">{{ $paradero->nombre }}</div>
                        <div class="paradero-info">
                            @if ($paradero->latitud && $paradero->longitud)
                                {{ number_format($paradero->latitud, 4) }}, {{ number_format($paradero->longitud, 4) }}
                            @else
                                Coordenadas no disponibles
                            @endif
                        </div>
                    </div>
                    <img
                        src="{{ $paradero->imagen ? asset($paradero->imagen) : ($paradero->imagen_url ? (Str::startsWith($paradero->imagen_url, 'http') ? $paradero->imagen_url : asset($paradero->imagen_url)) : asset('images/paraderos/default.svg')) }}"
                        alt="{{ $paradero->nombre }}"
                        class="paradero-foto"
                        onerror="this.src='{{ asset('images/paraderos/default.svg') }}'"
                    >
                </div>
            @endforeach
        @endif
    </div>
</aside>

<div id="rp-resultado" class="rp-resultado-panel" style="display:none;"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Navbar hover trigger (CSS ~ no funciona porque el trigger está dentro de <main>)
    var nav = document.querySelector('nav.navbar-gowayki');
    var trigger = document.getElementById('navbar-trigger');
    var hideTimeout = null;
    if (nav && trigger) {
        function showNav() { clearTimeout(hideTimeout); nav.classList.add('visible'); }
        function hideNav() { hideTimeout = setTimeout(function () { nav.classList.remove('visible'); }, 300); }
        trigger.addEventListener('mouseenter', showNav);
        trigger.addEventListener('mouseleave', hideNav);
        nav.addEventListener('mouseenter', showNav);
        nav.addEventListener('mouseleave', hideNav);
    }

    var ruta = @json($ruta);
    var paraderos = ruta.paraderos || [];
    var colorLinea = ruta.color_linea || '#F83A34';

    if (paraderos.length === 0) {
        document.getElementById('mapa-ruta').innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9ca3af;">No hay paraderos para mostrar en el mapa.</div>';
        return;
    }

    var mapa = L.map('mapa-ruta', {
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OSM</a>',
    }).addTo(mapa);

    // Pane para la línea verde (siempre al frente)
    mapa.createPane('greenPane');
    mapa.getPane('greenPane').style.zIndex = 700;

    var bounds = [];
    var markers = [];

    paraderos.forEach(function (p, i) {
        if (!p.latitud || !p.longitud) return;

        var lat = parseFloat(p.latitud);
        var lng = parseFloat(p.longitud);
        var coord = [lat, lng];
        bounds.push(coord);

        var imgSrc = p.imagen
            ? '{{ asset('') }}' + p.imagen
            : p.imagen_url
                ? (p.imagen_url.indexOf('http') === 0 ? p.imagen_url : '{{ asset('') }}' + p.imagen_url)
                : '{{ asset('images/paraderos/default.svg') }}';

        var iconHtml = [
            '<div class="gw-bubble-icon" style="border-color:' + colorLinea + '">',
            '  <img src="' + imgSrc + '" alt="' + p.nombre + '" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'" />',
            '</div>'
        ].join('');

        var icono = L.divIcon({
            html: iconHtml,
            className: '',
            iconSize:  [44, 44],
            iconAnchor:[22, 44],
            popupAnchor:[0, -44]
        });

        var marker = L.marker(coord, { icon: icono }).addTo(mapa);
        marker.bindPopup('<b>' + p.nombre + '</b>' + (p.orden ? '<br><small>Paradero #' + p.orden + '</small>' : ''));
        markers.push({ marker: marker, idx: i });
    });

    // Dibujar ruta realista con OSRM
    var rutasDisponibles = [];
    var rutaActivaIdx = 0;
    var rutaLineas = [];
    var altColors = ['#F83A34', '#3B82F6', '#10B981'];

    function dibujarLineaRecta() {
        if (bounds.length >= 2) {
            var l = L.polyline(bounds, {
                color: colorLinea,
                weight: 4,
                opacity: 0.7,
            }).addTo(mapa);
            rutaLineas.push(l);
        }
        document.getElementById('ruta-alternatives').style.display = 'none';
        ajustarVista();
    }

    function dibujarRutaOSRM() {
        if (bounds.length < 2) { ajustarVista(); return; }

        var coordsStr = paraderos
            .filter(function (p) { return p.latitud && p.longitud; })
            .map(function (p) { return p.longitud + ',' + p.latitud; })
            .join(';');

        var url = 'https://router.project-osrm.org/route/v1/driving/' + coordsStr + '?alternatives=3&geometries=geojson&overview=full&steps=false';

        fetch(url)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data && data.code === 'Ok' && data.routes && data.routes.length > 0) {
                    rutasDisponibles = data.routes;
                    mostrarAlternativas();
                    mostrarRuta(0);
                } else {
                    dibujarLineaRecta();
                }
                ajustarVista();
            })
            .catch(function () {
                dibujarLineaRecta();
                ajustarVista();
            });
    }

    function mostrarRuta(idx) {
        // Limpiar rutas anteriores
        rutaLineas.forEach(function (l) { mapa.removeLayer(l); });
        rutaLineas = [];
        rutaActivaIdx = idx;

        var route = rutasDisponibles[idx];
        if (!route) return;

        var coords = route.geometry.coordinates;
        var latlngs = coords.map(function (c) { return [c[1], c[0]]; });

        var l = L.polyline(latlngs, {
            color: altColors[idx] || colorLinea,
            weight: 5,
            opacity: 0.9,
        }).addTo(mapa);
        rutaLineas.push(l);

        // Actualizar active en UI
        document.querySelectorAll('.ruta-alt-item').forEach(function (el, i) {
            el.classList.toggle('active', i === idx);
        });
    }

    function mostrarAlternativas() {
        var list = document.getElementById('alt-list');
        var altDiv = document.getElementById('ruta-alternatives');
        list.innerHTML = '';

        // Calcular distancia real entre paraderos consecutivos (Haversine)
        var distanciasSegmento = [];
        for (var k = 0; k < bounds.length - 1; k++) {
            var d = haversineKm(bounds[k][0], bounds[k][1], bounds[k+1][0], bounds[k+1][1]);
            distanciasSegmento.push(d);
        }
        var distanciaTotalKm = distanciasSegmento.reduce(function (a, b) { return a + b; }, 0);
        // Sumar un 30% por curvas/calles (más realista que línea recta)
        var distanciaRealKm = (distanciaTotalKm * 1.3).toFixed(1);
        // Usar distancia calculada para estimar tiempo (3 min/km ≈ 20 km/h urbano)
        var tiempoEstimado = Math.round(distanciaRealKm * 3);
        // Si el DB tiene un valor razonable (entre 0.5x y 2x del calculado), usarlo
        var dbTiempo = ruta.tiempo_estimado_minutos;
        if (dbTiempo && dbTiempo >= Math.round(tiempoEstimado * 0.5) && dbTiempo <= Math.round(tiempoEstimado * 2)) {
            tiempoEstimado = dbTiempo;
        }

        document.getElementById('alt-count').textContent = rutasDisponibles.length + ' opciones';

        rutasDisponibles.forEach(function (route, i) {
            var esMasRapida = i === 0;
            var label = esMasRapida ? 'Ruta recomendada' : 'Ruta ' + (i + 1);

            var item = document.createElement('div');
            item.className = 'ruta-alt-item' + (i === 0 ? ' active' : '');
            item.innerHTML =
                '<span class="ruta-alt-color" style="background:' + (altColors[i] || colorLinea) + '"></span>' +
                '<div class="ruta-alt-info">' +
                '  <div class="ruta-alt-label">' + label + '</div>' +
                '  <div class="ruta-alt-detail">' + tiempoEstimado + ' min · ' + distanciaRealKm + ' km</div>' +
                '</div>' +
                (esMasRapida ? '<span class="ruta-alt-badge">Más rápida</span>' : '');
            item.addEventListener('click', function () { mostrarRuta(i); });
            list.appendChild(item);
        });

        altDiv.style.display = 'block';
    }

    function haversineKm(lat1, lng1, lat2, lng2) {
        var R = 6371;
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLng = (lng2 - lng1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function ajustarVista() {
        if (bounds.length > 0) {
            var group = L.featureGroup(bounds.map(function (c) { return L.marker(c); }));
            mapa.fitBounds(group.getBounds().pad(0.15));
        }
    }

    dibujarRutaOSRM();

    // Click en sidebar → centrar/popup en mapa
    document.querySelectorAll('.paradero-item').forEach(function (el) {
        el.addEventListener('click', function () {
            var lat = parseFloat(el.dataset.lat);
            var lng = parseFloat(el.dataset.lng);
            var idx = parseInt(el.dataset.idx);
            if (isNaN(lat) || isNaN(lng)) return;

            mapa.setView([lat, lng], 17, { animate: true });
            if (markers[idx]) {
                markers[idx].marker.openPopup();
            }
        });
    });

    // Planificador con autocomplete y persistencia en URL
    var todosParaderos = @json($todosParaderos);
    var rutaCortaLinea = null;
    var rpOrigenInput = document.getElementById('rp-origen-input');
    var rpDestinoInput = document.getElementById('rp-destino-input');
    var rpOrigenSug = document.getElementById('rp-origen-suggestions');
    var rpDestinoSug = document.getElementById('rp-destino-suggestions');
    var rpResultado = document.getElementById('rp-resultado');
    var rpSelected = { origen: null, destino: null };

    function obtenerImagenUrl(p) {
        if (p.imagen) return '{{ asset('') }}' + p.imagen;
        if (p.imagen_url) return p.imagen_url.indexOf('http') === 0 ? p.imagen_url : '{{ asset('') }}' + p.imagen_url;
        return '{{ asset('images/paraderos/default.svg') }}';
    }

    function filtrarParaderos(query) {
        if (!query) return [];
        var q = query.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        return todosParaderos.filter(function (p) {
            var name = p.nombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            return name.indexOf(q) !== -1;
        });
    }

    function renderSugerencias(input, suggestionsEl, target) {
        var query = input.value.trim();
        var resultados = filtrarParaderos(query);
        suggestionsEl.innerHTML = '';
        if (resultados.length === 0 || query.length < 1) {
            suggestionsEl.classList.remove('open');
            return;
        }
        suggestionsEl.classList.add('open');
        resultados.forEach(function (p) {
            var item = document.createElement('div');
            item.className = 'rp-suggestion-item';
            item.innerHTML = '<span class="rp-sug-label">' + p.nombre + '</span><span class="rp-sug-ruta">Ruta ' + p.ruta_id + '</span>';
            item.addEventListener('click', function () {
                input.value = p.nombre;
                rpSelected[target] = { id: p.id, nombre: p.nombre, imagen: obtenerImagenUrl(p) };
                suggestionsEl.classList.remove('open');
                actualizarURL();
                calcularMejorRuta();
            });
            suggestionsEl.appendChild(item);
        });
    }

    rpOrigenInput.addEventListener('input', function () {
        rpSelected.origen = null;
        renderSugerencias(rpOrigenInput, rpOrigenSug, 'origen');
    });

    rpDestinoInput.addEventListener('input', function () {
        rpSelected.destino = null;
        renderSugerencias(rpDestinoInput, rpDestinoSug, 'destino');
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.rp-autocomplete')) {
            rpOrigenSug.classList.remove('open');
            rpDestinoSug.classList.remove('open');
        }
    });

    // Navegación con teclado en sugerencias
    function tecladoSugerencias(e, input, suggestionsEl, target) {
        var items = suggestionsEl.querySelectorAll('.rp-suggestion-item');
        if (items.length === 0) return;
        var active = suggestionsEl.querySelector('.rp-suggestion-item.active');
        var idx = Array.from(items).indexOf(active);
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            var next = (active ? idx + 1 : 0);
            if (next >= items.length) next = 0;
            if (active) active.classList.remove('active');
            items[next].classList.add('active');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            var prev = active ? idx - 1 : items.length - 1;
            if (prev < 0) prev = items.length - 1;
            if (active) active.classList.remove('active');
            items[prev].classList.add('active');
        } else if (e.key === 'Enter') {
            if (active) {
                e.preventDefault();
                active.click();
            }
        } else if (e.key === 'Escape') {
            suggestionsEl.classList.remove('open');
        }
    }

    rpOrigenInput.addEventListener('keydown', function (e) { tecladoSugerencias(e, rpOrigenInput, rpOrigenSug, 'origen'); });
    rpDestinoInput.addEventListener('keydown', function (e) { tecladoSugerencias(e, rpDestinoInput, rpDestinoSug, 'destino'); });

    // Persistencia en URL
    function actualizarURL() {
        var params = new URLSearchParams(window.location.search);
        if (rpSelected.origen) {
            params.set('origen', rpSelected.origen.nombre);
            params.set('origen_id', rpSelected.origen.id);
        } else {
            params.delete('origen');
            params.delete('origen_id');
        }
        if (rpSelected.destino) {
            params.set('destino', rpSelected.destino.nombre);
            params.set('destino_id', rpSelected.destino.id);
        } else {
            params.delete('destino');
            params.delete('destino_id');
        }
        var nuevaURL = window.location.pathname + '?' + params.toString();
        if (params.toString()) {
            window.history.replaceState(null, '', nuevaURL);
        } else {
            window.history.replaceState(null, '', window.location.pathname);
        }
    }

    // Leer params de URL al cargar
    (function cargarDeURL() {
        var params = new URLSearchParams(window.location.search);
        var oNombre = params.get('origen');
        var dNombre = params.get('destino');
        var oId = params.get('origen_id');
        var dId = params.get('destino_id');

        function matchParadero(nombre, id) {
            if (id) {
                var p = todosParaderos.find(function (x) { return x.id == id; });
                if (p) return p;
            }
            if (nombre) {
                var n = nombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                return todosParaderos.find(function (x) {
                    var xn = x.nombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    return xn.indexOf(n) !== -1;
                });
            }
            return null;
        }

        var o = matchParadero(oNombre, oId);
        var d = matchParadero(dNombre, dId);

        // Si no hay origen/destino por URL, usar primero y último paradero de la ruta actual
        if (!o && paraderos.length >= 2) {
            var firstP = paraderos[0];
            var lastP = paraderos[paraderos.length - 1];
            o = todosParaderos.find(function (x) { return x.id == firstP.id; });
            d = todosParaderos.find(function (x) { return x.id == lastP.id; });
        }

        if (o) {
            rpOrigenInput.value = o.nombre;
            rpSelected.origen = { id: o.id, nombre: o.nombre, imagen: obtenerImagenUrl(o) };
        }
        if (d) {
            rpDestinoInput.value = d.nombre;
            rpSelected.destino = { id: d.id, nombre: d.nombre, imagen: obtenerImagenUrl(d) };
        }

        if (rpSelected.origen && rpSelected.destino) {
            calcularMejorRuta();
        }
    })();

    function calcularMejorRuta() {
        var origenId = rpSelected.origen ? rpSelected.origen.id : null;
        var destinoId = rpSelected.destino ? rpSelected.destino.id : null;

        if (!origenId || !destinoId) { rpResultado.style.display = 'none'; return; }
        if (origenId === destinoId) {
            rpResultado.style.display = 'block';
            rpResultado.className = 'rp-resultado-panel rp-error';
            rpResultado.innerHTML = 'Origen y destino no pueden ser iguales.';
            return;
        }

        rpResultado.style.display = 'block';
        rpResultado.className = 'rp-resultado-panel rp-error';
        rpResultado.innerHTML = 'Calculando...';

        fetch('/api/ruta-corta?origen_id=' + origenId + '&destino_id=' + destinoId)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.error) {
                    rpResultado.style.display = 'block';
                    rpResultado.className = 'rp-resultado-panel rp-error';
                    rpResultado.textContent = data.error;
                    return;
                }

                var nombres = data.paraderos.map(function (p) { return p.nombre; });
                var oImg = rpSelected.origen.imagen || '{{ asset('images/paraderos/default.svg') }}';
                var dImg = rpSelected.destino.imagen || '{{ asset('images/paraderos/default.svg') }}';

                rpResultado.style.display = 'block';
                rpResultado.className = 'rp-resultado-panel rp-ok';
                rpResultado.innerHTML =
                    '<div class="rp-imagenes">' +
                    '  <img class="rp-img-cuadro" src="' + oImg + '" alt="origen" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'">' +
                    '  <span class="rp-img-flecha">→</span>' +
                    '  <img class="rp-img-cuadro" src="' + dImg + '" alt="destino" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'">' +
                    '</div>' +
                    '<div class="rp-resumen">' +
                    data.distancia_km + ' km · ' + data.tiempo_min + ' min · ' + data.nodos_recorridos + ' paraderos</div>' +
                    '<div class="rp-path">' +
                    nombres.map(function (n) { return '<span class="step">' + n + '</span>'; }).join(' → ') +
                    '</div>';

                rpBuscarOtra.style.display = 'block';

                if (rutaCortaLinea) { mapa.removeLayer(rutaCortaLinea); }

                var coordsArr = data.paraderos
                    .filter(function (p) { return p.latitud && p.longitud; })
                    .map(function (p) { return [parseFloat(p.latitud), parseFloat(p.longitud)]; });

                // Dibujar línea recta verde como base
                rutaCortaLinea = L.polyline(coordsArr, {
                    color: '#10B981',
                    weight: 6,
                    opacity: 0.9,
                    pane: 'greenPane',
                }).addTo(mapa);
                mapa.fitBounds(rutaCortaLinea.getBounds().pad(0.15));

                // Intentar OSRM para ruta más realista (reemplaza la recta si funciona)
                var coordsStr = data.paraderos
                    .filter(function (p) { return p.latitud && p.longitud; })
                    .map(function (p) { return p.longitud + ',' + p.latitud; })
                    .join(';');

                var osrmUrl = 'https://router.project-osrm.org/route/v1/driving/' + coordsStr + '?geometries=geojson&overview=full&steps=false';
                fetch(osrmUrl)
                    .then(function (r) { return r.json(); })
                    .then(function (osrmData) {
                        if (osrmData && osrmData.code === 'Ok' && osrmData.routes && osrmData.routes[0]) {
                            if (rutaCortaLinea) { mapa.removeLayer(rutaCortaLinea); }
                            var coords = osrmData.routes[0].geometry.coordinates;
                            var latlngs = coords.map(function (c) { return [c[1], c[0]]; });
                            rutaCortaLinea = L.polyline(latlngs, {
                                color: '#10B981',
                                weight: 6,
                                opacity: 0.9,
                                pane: 'greenPane',
                            }).addTo(mapa);
                            mapa.fitBounds(rutaCortaLinea.getBounds().pad(0.15));
                        }
                    })
                    .catch(function () {});
            })
            .catch(function () {
                rpResultado.style.display = 'block';
                rpResultado.className = 'rp-resultado-panel rp-error';
                rpResultado.textContent = 'Error al calcular la ruta.';
            });
    }

    // Botón "Buscar otra ruta"
    var rpBuscarOtra = document.getElementById('rp-buscar-otra');

    rpBuscarOtra.addEventListener('click', function () {
        rpOrigenInput.value = '';
        rpDestinoInput.value = '';
        rpSelected.origen = null;
        rpSelected.destino = null;
        rpResultado.style.display = 'none';
        rpResultado.className = 'rp-resultado-panel';
        rpBuscarOtra.style.display = 'none';
        if (rutaCortaLinea) { mapa.removeLayer(rutaCortaLinea); rutaCortaLinea = null; }
        window.history.replaceState(null, '', window.location.pathname);
        rpOrigenInput.focus();
    });
});
</script>
@endpush
