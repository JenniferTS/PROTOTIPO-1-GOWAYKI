@extends('layouts.app')

@section('title', ($destino->nombre ?? 'Destino') . ' — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center space-x-2 text-gray-500 mb-4">
            <a href="{{ route('destinos.index') }}" class="hover:text-[#F83A34]">Destinos</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">{{ $destino->nombre }}</span>
        </div>

        <div class="h-64 md:h-96 rounded-xl bg-cover bg-center shadow-md mb-8" style="background-image: url('{{ $destino->imagen_url ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Arequipa_Plaza_de_Armas.jpg/1280px-Arequipa_Plaza_de_Armas.jpg' }}');"></div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $destino->nombre }}</h1>
                        <p class="text-gray-500 mt-1">{{ $destino->distrito ?? '—' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block bg-[#FFE7E5] text-red-700 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $destino->categoria ? ucfirst($destino->categoria) : 'Sin categoría' }}
                        </span>
                        @if (($destino->nombre ?? '') === 'Plaza de Armas de Cayma')
                            <span class="inline-block bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full ml-2">
                                Patrimonio Cultural de la Nación
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-1 text-yellow-500 text-lg mb-4">
                    @php $cal = $destino->calificacion ?? 0; @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($cal))
                            <span>★</span>
                        @elseif ($i - 0.5 <= $cal)
                            <span>★</span>
                        @else
                            <span>☆</span>
                        @endif
                    @endfor
                    <span class="text-gray-600 ml-2">{{ number_format($cal, 1) }}</span>
                </div>

                <p class="text-gray-700 leading-relaxed text-lg">{{ $destino->descripcion ?? 'Sin descripción disponible.' }}</p>
            </div>

            <div>
                <div id="mapa" class="w-full h-64 rounded-xl shadow-md mb-6"></div>

                @auth
                    @if ($visitado)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                            <p class="text-green-600 font-bold text-lg">✓ Visitado</p>
                            <form method="POST" action="{{ route('perfil.desmarcar', $destino->id) }}" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#F83A34] hover:underline text-sm">Desmarcar</button>
                            </form>
                        </div>
                    @else
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Marcar como visitado</h3>
                            <form method="POST" action="{{ route('perfil.visitar') }}">
                                @csrf
                                <input type="hidden" name="destino_id" value="{{ $destino->id }}">
                                <div class="mb-3">
                                    <label class="block text-gray-600 text-sm mb-1">Fecha de visita</label>
                                    <input type="date" name="fecha_visita" value="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent @error('fecha_visita') border-red-500 @enderror">
                                    @error('fecha_visita')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-600 text-sm mb-1">Notas</label>
                                    <textarea name="notas" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent @error('notas') border-red-500 @enderror" placeholder="¿Qué te pareció?">{{ old('notas') }}</textarea>
                                    @error('notas')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 rounded-lg transition">
                                    Marcar como visitado
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                        <p class="text-gray-700">Inicia sesión para registrar tu visita.</p>
                        <a href="{{ route('login') }}" class="inline-block mt-3 bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-6 rounded-lg transition">Ingresar</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = parseFloat('{{ $destino->latitud ?? '-16.4090' }}');
            const lng = parseFloat('{{ $destino->longitud ?? '-71.5375' }}');
            const mapa = L.map('mapa').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapa);

            if (!isNaN(lat) && !isNaN(lng)) {
                L.marker([lat, lng])
                    .addTo(mapa)
                    .bindPopup('<b>{{ $destino->nombre ?? 'Destino' }}</b>');
            }
        });
    </script>
@endpush

