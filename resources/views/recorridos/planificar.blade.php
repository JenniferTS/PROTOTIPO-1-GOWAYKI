@extends('layouts.app')

@section('title', 'Planificar Recorrido — GoWayki')

@push('styles')
<style>
#mapa-planificar {
    width: 100%;
    height: 500px;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.planificar-resultado {
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.1);
    padding: 16px 20px;
}

.planificar-resultado .rp-imagenes {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.planificar-resultado .rp-img-cuadro {
    width: 52px;
    height: 52px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    flex-shrink: 0;
}

.planificar-resultado .rp-img-flecha {
    color: #9ca3af;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.planificar-resultado .rp-resumen {
    font-weight: 700;
    color: #1f2937;
    font-size: 1rem;
    margin-bottom: 4px;
}

.planificar-resultado .rp-path {
    color: #374151;
    line-height: 1.6;
    font-size: 0.78rem;
    max-height: 60px;
    overflow-y: auto;
}

.planificar-resultado .rp-path .step {
    display: inline-block;
    background: #dcfce7;
    padding: 2px 8px;
    border-radius: 4px;
    margin: 1px 0;
    color: #16a34a;
}

.rp-autocomplete {
    position: relative;
    flex: 1;
}

.rp-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.88rem;
    color: #1f2937;
    background: #fff;
    box-sizing: border-box;
}

.rp-input:focus {
    outline: none;
    border-color: #F83A34;
    box-shadow: 0 0 0 2px rgba(248,58,52,0.12);
}

.rp-suggestions {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    max-height: 200px;
    overflow-y: auto;
    z-index: 700;
}

.rp-suggestions.open { display: block; }

.rp-suggestion-item {
    padding: 8px 12px;
    font-size: 0.82rem;
    color: #374151;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
}

.rp-suggestion-item:last-child { border-bottom: none; }
.rp-suggestion-item:hover,
.rp-suggestion-item.active { background: #fef2f2; color: #F83A34; }

.rp-suggestion-item .rp-sug-label { font-weight: 600; }
.rp-suggestion-item .rp-sug-ruta { font-size: 0.68rem; color: #9ca3af; margin-left: 4px; }

.planificar-ruta-detalle {
    background: #f9fafb;
    border-radius: 12px;
    padding: 16px;
    font-size: 0.85rem;
    color: #374151;
}

.paradero-item-sm {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background 0.15s;
}

.paradero-item-sm:last-child { border-bottom: none; }
.paradero-item-sm:hover { background: #fff; border-radius: 6px; padding-left: 6px; padding-right: 6px; }

.paradero-item-sm .num {
    width: 24px;
    height: 24px;
    min-width: 24px;
    background: #FADBD8;
    color: #F83A34;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.7rem;
}

.paradero-item-sm .nombre {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.82rem;
}

.paradero-item-sm .foto-med {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 6px;
    object-fit: cover;
    border: 1px solid #e5e7eb;
}

.mapa-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-style: italic;
    background: #f9fafb;
    border-radius: 12px;
}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Planificar Recorrido</h1>
        <p class="text-gray-600 mt-1">Encuentra la mejor ruta entre dos paraderos de Arequipa.</p>
    </div>

    <form method="GET" action="{{ route('recorridos.planificar') }}" class="bg-white rounded-xl shadow-md p-5 mb-6">
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1 text-sm" for="plan-origen">Origen</label>
                <div style="position:relative;">
                    <input type="text" id="plan-origen" name="origen" value="{{ $request->origen ?? '' }}"
                        class="rp-input @error('origen') border-red-500 @enderror"
                        placeholder="Ej: Mercado Avelino" required minlength="2" autocomplete="off">
                    <input type="hidden" id="plan-origen_id" name="origen_id" value="{{ $request->origen_id ?? '' }}">
                    <div id="plan-origen-sug" class="rp-suggestions"></div>
                </div>
                @error('origen')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-1 text-sm" for="plan-destino">Destino</label>
                <div style="position:relative;">
                    <input type="text" id="plan-destino" name="destino" value="{{ $request->destino ?? '' }}"
                        class="rp-input @error('destino') border-red-500 @enderror"
                        placeholder="Ej: TECSUP" required minlength="2" autocomplete="off">
                    <input type="hidden" id="plan-destino_id" name="destino_id" value="{{ $request->destino_id ?? '' }}">
                    <div id="plan-destino-sug" class="rp-suggestions"></div>
                </div>
                @error('destino')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2.5 px-6 rounded-lg transition">
                    Planificar
                </button>
            </div>
        </div>
    </form>

    <div id="planificar-resultado" class="planificar-resultado bg-white mb-6" style="display:none;"></div>

    @if ($errors->any() && !$errors->has('origen') && !$errors->has('destino'))
        <div class="bg-[#FFE7E5] border-l-4 border-red-500 text-[#D82027] px-4 py-3 rounded-lg mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <div id="planificar-contenido" class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div id="mapa-planificar">
                <div class="mapa-placeholder">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <p>Ingresa origen y destino para ver la ruta en el mapa.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div id="planificar-ruta-info" class="planificar-ruta-detalle">
                <p class="text-gray-400 text-sm italic text-center py-8">Los detalles de la ruta aparecerán aquí.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var todosParaderos = @json($todosParaderos);
    if (!todosParaderos) return;

    var mapa = L.map('mapa-planificar', {
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OSM</a>',
    }).addTo(mapa);

    mapa.createPane('greenPane');
    mapa.getPane('greenPane').style.zIndex = 700;

    mapa.setView([-16.409, -71.532], 13);

    var rutaCortaLinea = null;
    var markersPlan = [];
    var planResultado = document.getElementById('planificar-resultado');
    var csrfToken = '{{ csrf_token() }}';

    function obtenerRutaUrl(data) {
        var rutaId = obtenerRutaId(data);
        return rutaId ? '{{ url('rutas') }}/' + rutaId : '#';
    }

    function obtenerRutaId(data) {
        if (!data.paraderos || data.paraderos.length === 0) return null;
        var rutaIds = {};
        data.paraderos.forEach(function (p) {
            if (p.ruta_id) rutaIds[p.ruta_id] = (rutaIds[p.ruta_id] || 0) + 1;
        });
        var maxCount = 0, bestId = null;
        for (var id in rutaIds) {
            if (rutaIds[id] > maxCount) { maxCount = rutaIds[id]; bestId = id; }
        }
        return bestId;
    }

    var planOrigenInput = document.getElementById('plan-origen');
    var planDestinoInput = document.getElementById('plan-destino');
    var planOrigenSug = document.getElementById('plan-origen-sug');
    var planDestinoSug = document.getElementById('plan-destino-sug');
    var planOrigenHidden = document.getElementById('plan-origen_id');
    var planDestinoHidden = document.getElementById('plan-destino_id');
    var planRutaInfo = document.getElementById('planificar-ruta-info');

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

    planOrigenInput.addEventListener('input', function () {
        rpSelected.origen = null;
        renderSugerencias(planOrigenInput, planOrigenSug, 'origen');
    });

    planDestinoInput.addEventListener('input', function () {
        rpSelected.destino = null;
        renderSugerencias(planDestinoInput, planDestinoSug, 'destino');
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#plan-origen') && !e.target.closest('#plan-origen-sug') &&
            !e.target.closest('#plan-destino') && !e.target.closest('#plan-destino-sug')) {
            planOrigenSug.classList.remove('open');
            planDestinoSug.classList.remove('open');
        }
    });

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
            if (active) { e.preventDefault(); active.click(); }
        } else if (e.key === 'Escape') {
            suggestionsEl.classList.remove('open');
        }
    }

    planOrigenInput.addEventListener('keydown', function (e) { tecladoSugerencias(e, planOrigenInput, planOrigenSug, 'origen'); });
    planDestinoInput.addEventListener('keydown', function (e) { tecladoSugerencias(e, planDestinoInput, planDestinoSug, 'destino'); });

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

        if (o) {
            planOrigenInput.value = o.nombre;
            rpSelected.origen = { id: o.id, nombre: o.nombre, imagen: obtenerImagenUrl(o) };
        }
        if (d) {
            planDestinoInput.value = d.nombre;
            rpSelected.destino = { id: d.id, nombre: d.nombre, imagen: obtenerImagenUrl(d) };
        }

        if (rpSelected.origen && rpSelected.destino) {
            calcularMejorRuta();
        }
    })();

    function calcularMejorRuta() {
        var origenId = rpSelected.origen ? rpSelected.origen.id : null;
        var destinoId = rpSelected.destino ? rpSelected.destino.id : null;

        if (!origenId || !destinoId) { planResultado.style.display = 'none'; return; }
        if (origenId === destinoId) {
            planResultado.style.display = 'block';
            planResultado.innerHTML = '<div class="text-red-600 font-semibold text-sm">Origen y destino no pueden ser iguales.</div>';
            return;
        }

        planResultado.style.display = 'block';
        planResultado.innerHTML = '<div class="text-gray-500">Calculando...</div>';

        fetch('/api/ruta-corta?origen_id=' + origenId + '&destino_id=' + destinoId)
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.error) {
                    planResultado.style.display = 'block';
                    planResultado.innerHTML = '<div class="text-red-600 font-semibold text-sm">' + data.error + '</div>';
                    return;
                }

                var nombres = data.paraderos.map(function (p) { return p.nombre; });
                var oImg = rpSelected.origen.imagen || '{{ asset('images/paraderos/default.svg') }}';
                var dImg = rpSelected.destino.imagen || '{{ asset('images/paraderos/default.svg') }}';

                planResultado.style.display = 'block';
                planResultado.innerHTML =
                    '<div class="rp-imagenes">' +
                    '  <img class="rp-img-cuadro" src="' + oImg + '" alt="origen" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'">' +
                    '  <span class="rp-img-flecha">→</span>' +
                    '  <img class="rp-img-cuadro" src="' + dImg + '" alt="destino" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'">' +
                    '</div>' +
                    '<div class="rp-resumen">' +
                    data.distancia_km + ' km · ' + data.tiempo_min + ' min · ' + data.nodos_recorridos + ' paraderos</div>' +
                    '<div class="rp-path">' +
                    nombres.map(function (n) { return '<span class="step">' + n + '</span>'; }).join(' → ') +
                    '</div>' +
                    '<div class="flex gap-2 mt-3">' +
                    '  <a href="' + obtenerRutaUrl(data) + '" class="flex-1 text-center border border-[#F83A34] text-[#F83A34] hover:bg-[#F83A34] hover:text-white font-semibold py-1.5 rounded-lg text-sm transition">Ver detalle</a>' +
                    ({{ auth()->check() ? 'true' : 'false' }}
                        ? '<form method="POST" action="{{ route('recorridos.guardar') }}" class="flex-1"><input type="hidden" name="_token" value="' + csrfToken + '"><input type="hidden" name="origen" value="' + rpSelected.origen.nombre + '"><input type="hidden" name="destino" value="' + rpSelected.destino.nombre + '"><input type="hidden" name="ruta_id" value="' + obtenerRutaId(data) + '"><button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-1.5 rounded-lg text-sm transition">Agregar a mi lista</button></form>'
                        : '<a href="{{ route('login') }}" class="flex-1 text-center bg-gray-200 text-gray-500 font-semibold py-1.5 rounded-lg text-sm cursor-not-allowed block">Inicia sesión para guardar</a>'
                    ) +
                    '</div>';

                if (rutaCortaLinea) { mapa.removeLayer(rutaCortaLinea); }

                var coordsArr = data.paraderos
                    .filter(function (p) { return p.latitud && p.longitud; })
                    .map(function (p) { return [parseFloat(p.latitud), parseFloat(p.longitud)]; });

                rutaCortaLinea = L.polyline(coordsArr, {
                    color: '#10B981',
                    weight: 6,
                    opacity: 0.9,
                    pane: 'greenPane',
                }).addTo(mapa);
                mapa.fitBounds(rutaCortaLinea.getBounds().pad(0.15));

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

                renderRutaInfo(data);
            })
            .catch(function () {
                planResultado.style.display = 'block';
                planResultado.innerHTML = '<div class="text-red-600 font-semibold text-sm">Error al calcular la ruta.</div>';
            });
    }

    function renderRutaInfo(data) {
        var html = '<h4 class="font-bold text-gray-800 mb-2 text-sm uppercase tracking-wide">Recorrido</h4>';
        data.paraderos.forEach(function (p) {
            var imgSrc = obtenerImagenUrl(p);
            html +=
                '<div class="paradero-item-sm" data-lat="' + p.latitud + '" data-lng="' + p.longitud + '">' +
                '  <span class="num">' + (p.orden || '') + '</span>' +
                '  <img class="foto-med" src="' + imgSrc + '" alt="' + p.nombre + '" onerror="this.src=\'{{ asset('images/paraderos/default.svg') }}\'">' +
                '  <span class="nombre">' + p.nombre + '</span>' +
                '</div>';
        });
        html +=
            '<div class="flex gap-2 mt-4">' +
            '  <a href="' + obtenerRutaUrl(data) + '" class="flex-1 text-center border border-[#F83A34] text-[#F83A34] hover:bg-[#F83A34] hover:text-white font-semibold py-1.5 rounded-lg text-sm transition">Ver detalle</a>' +
            ({{ auth()->check() ? 'true' : 'false' }}
                ? '<form method="POST" action="{{ route('recorridos.guardar') }}" class="flex-1"><input type="hidden" name="_token" value="' + csrfToken + '"><input type="hidden" name="origen" value="' + rpSelected.origen.nombre + '"><input type="hidden" name="destino" value="' + rpSelected.destino.nombre + '"><input type="hidden" name="ruta_id" value="' + obtenerRutaId(data) + '"><button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-1.5 rounded-lg text-sm transition">Agregar a mi lista</button></form>'
                : '<a href="{{ route('login') }}" class="flex-1 text-center bg-gray-200 text-gray-500 font-semibold py-1.5 rounded-lg text-sm cursor-not-allowed block">Inicia sesión para guardar</a>'
            ) +
            '</div>';
        planRutaInfo.innerHTML = html;

        planRutaInfo.querySelectorAll('.paradero-item-sm').forEach(function (el) {
            el.addEventListener('click', function () {
                var lat = parseFloat(el.dataset.lat);
                var lng = parseFloat(el.dataset.lng);
                if (!isNaN(lat) && !isNaN(lng)) {
                    mapa.setView([lat, lng], 17, { animate: true });
                }
            });
        });
    }
});
</script>
@endpush
