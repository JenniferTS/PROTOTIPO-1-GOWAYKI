@extends('layouts.app')

@section('title', 'Planificar Recorrido — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Planificar Recorrido</h1>
        <p class="text-gray-600 mb-8">Encuentra la mejor ruta para tu viaje en Arequipa.</p>

        @error('origen')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center space-x-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $message }}</span>
            </div>
        @enderror
        @error('destino')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center space-x-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <form method="GET" action="{{ route('recorridos.planificar') }}" class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="origen">Punto de partida</label>
                    <select name="origen" id="origen" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent @error('origen') border-red-500 @enderror">
                        <option value="">Seleccionar origen</option>
                        @foreach ($origenes as $origen)
                            <option value="{{ $origen }}" {{ ($request->origen ?? '') === $origen ? 'selected' : '' }}>{{ $origen }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="destino">Destino</label>
                    <select name="destino" id="destino" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent @error('destino') border-red-500 @enderror">
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
            @if ($resultado['degradado'] ?? false)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-center space-x-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Mostrando información en caché. Algunos datos podrían no estar actualizados.</span>
                </div>
            @endif

            <div class="mb-4">
                <p class="text-lg text-gray-700">
                    <span class="font-bold">{{ $resultado['total_opciones'] }}</span> {{ $resultado['total_opciones'] === 1 ? 'opción encontrada' : 'opciones encontradas' }}
                    para <span class="font-semibold">{{ $resultado['origen'] }}</span> → <span class="font-semibold">{{ $resultado['destino'] }}</span>
                </p>
            </div>

            @if ($resultado['rutas']->isEmpty())
                <div class="text-center py-16 bg-white rounded-xl shadow-md">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <p class="text-gray-500 text-lg font-medium">No encontramos una ruta directa entre <span class="font-semibold">{{ $resultado['origen'] }}</span> y <span class="font-semibold">{{ $resultado['destino'] }}</span>.</p>
                    <p class="text-gray-400 mt-2">Intenta con un punto de referencia cercano o consulta el listado completo de rutas.</p>
                    <div class="mt-6 flex justify-center space-x-4">
                        <a href="{{ route('rutas.index') }}" class="bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold px-6 py-2 rounded-lg transition">
                            Ver todas las rutas
                        </a>
                        <a href="{{ route('destinos.index') }}" class="border border-[#E74C3C] text-[#E74C3C] hover:bg-[#E74C3C] hover:text-white font-semibold px-6 py-2 rounded-lg transition">
                            Explorar destinos
                        </a>
                    </div>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach ($resultado['rutas'] as $ruta)
                        <div class="card-gowayki p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-800">{{ $ruta->nombre }}</h3>
                                <span class="w-4 h-4 rounded-full" style="background-color: {{ $ruta->color_linea ?? '#E74C3C' }}"></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600 mb-3">
                                <span>{{ $ruta->origen }}</span>
                                <svg class="w-4 h-4 text-[#E74C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span>{{ $ruta->destino ?? '—' }}</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                <span>⏱ {{ $ruta->tiempo_estimado_minutos ?? '—' }} min</span>
                                <span class="font-semibold text-[#E74C3C]">{{ $ruta->costo_formateado ?? 'S/ 0.00' }}</span>
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
                                            Confirmar y guardar
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="flex-1 text-center bg-gray-200 text-gray-500 font-semibold py-2 rounded-lg transition cursor-not-allowed block">
                                        Inicia sesión para guardar
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <div
                    id="gowayki-mapa-ruta-root"
                    data-ruta-id="{{ $resultado['rutas']->first()->id ?? '' }}"
                    data-modo="planificar"
                    data-origen="{{ $resultado['origen'] ?? '' }}"
                    data-destino="{{ $resultado['destino'] ?? '' }}"
                    style="width: 100%; min-height: 600px;"
                ></div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite('resources/js/islands/mapa-ruta/index.jsx')
@endpush
