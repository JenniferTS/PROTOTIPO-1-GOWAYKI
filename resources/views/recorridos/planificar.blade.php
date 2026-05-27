@extends('layouts.app')

@section('title', 'Planificar Recorrido — GoWayki')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Planificar Recorrido</h1>
        <p class="text-gray-600 mb-8">Encuentra la mejor ruta para tu viaje en Arequipa.</p>

        <form method="GET" action="{{ route('recorridos.planificar') }}" class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Punto de partida</label>
                    <select name="origen" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                        <option value="">Seleccionar origen</option>
                        @foreach ($origenes as $origen)
                            <option value="{{ $origen }}" {{ ($request->origen ?? '') === $origen ? 'selected' : '' }}>{{ $origen }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Destino</label>
                    <select name="destino" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                        <option value="">Seleccionar destino</option>
                        @foreach ($destinos as $dest)
                            <option value="{{ $dest }}" {{ ($request->destino ?? '') === $dest ? 'selected' : '' }}>{{ $dest }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Planificar
                    </button>
                </div>
            </div>
        </form>

        @if ($resultado !== null)
            <div class="mb-4">
                <p class="text-lg text-gray-700">
                    <span class="font-bold">{{ $resultado['total_opciones'] }}</span> {{ $resultado['total_opciones'] === 1 ? 'opción encontrada' : 'opciones encontradas' }}
                    para <span class="font-semibold">{{ $resultado['origen'] }}</span> → <span class="font-semibold">{{ $resultado['destino'] }}</span>
                </p>
            </div>

            @if ($resultado['rutas']->isEmpty())
                <div class="text-center py-12 bg-white rounded-xl shadow-md">
                    <p class="text-gray-500 text-lg">No encontramos rutas para este trayecto.</p>
                    <p class="text-gray-400 mt-2">Prueba con otros puntos de origen o destino.</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach ($resultado['rutas'] as $ruta)
                        <div class="card-gowayki p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $ruta->nombre }}</h3>
                                <span class="w-4 h-4 rounded-full" style="background-color: {{ $ruta->color_linea }}"></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600 mb-3">
                                <span>{{ $ruta->origen }}</span>
                                <svg class="w-4 h-4 text-[#E74C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span>{{ $ruta->destino }}</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                <span>⏱ {{ $ruta->tiempo_estimado_minutos }} min</span>
                                <span class="font-semibold text-[#E74C3C]">{{ $ruta->costo_formateado }}</span>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('rutas.show', $ruta->id) }}" class="flex-1 text-center border border-[#E74C3C] text-[#E74C3C] hover:bg-[#E74C3C] hover:text-white font-semibold py-2 rounded-lg transition">
                                    Ver detalle
                                </a>
                                @auth
                                    <form method="POST" action="{{ route('recorridos.guardar') }}" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="origen" value="{{ $resultado['origen'] }}">
                                        <input type="hidden" name="destino" value="{{ $resultado['destino'] }}">
                                        <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                                        <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-2 rounded-lg transition">
                                            Guardar recorrido
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="mapa" class="w-full h-[400px] rounded-xl shadow-md mt-8"></div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @if ($resultado && $resultado['rutas']->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapa = L.map('mapa').setView([-16.4090, -71.5375], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapa);

                const rutas = @json($resultado['rutas']);
                const bounds = [];

                rutas.forEach(function(ruta) {
                    if (ruta.paraderos && ruta.paraderos.length > 0) {
                        const latlngs = [];
                        ruta.paraderos.forEach(function(p) {
                            latlngs.push([p.latitud, p.longitud]);
                            bounds.push([p.latitud, p.longitud]);
                        });

                        if (latlngs.length > 1) {
                            L.polyline(latlngs, {
                                color: ruta.color_linea,
                                weight: 3,
                                opacity: 0.7
                            }).addTo(mapa);
                        }
                    }
                });

                if (bounds.length > 0) {
                    mapa.fitBounds(bounds, { padding: [50, 50] });
                }

                @if ($resultado['rutas']->isNotEmpty() && $resultado['rutas']->first()->paraderos->isNotEmpty())
                    const first = @json($resultado['rutas']->first()->paraderos->first());
                    const last = @json($resultado['rutas']->last()->paraderos->last());
                    L.marker([first.latitud, first.longitud]).addTo(mapa).bindPopup('<b>Origen: {{ $resultado['origen'] }}</b>');
                    L.marker([last.latitud, last.longitud]).addTo(mapa).bindPopup('<b>Destino: {{ $resultado['destino'] }}</b>');
                @endif
            });
        </script>
    @endif
@endpush
