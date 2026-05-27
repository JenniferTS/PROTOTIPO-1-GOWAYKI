@extends('layouts.app')

@section('title', 'Rutas de Transporte — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rutas de Transporte</h1>
        <p class="text-gray-600 mb-8">Encuentra la mejor ruta de combi o bus para moverte por Arequipa.</p>

        <form method="GET" action="{{ route('rutas.index') }}" class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Punto de partida</label>
                    <input type="text" name="origen" value="{{ $request->origen ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent"
                        placeholder="Ej: Plaza de Armas">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Destino</label>
                    <input type="text" name="destino" value="{{ $request->destino ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent"
                        placeholder="Ej: TECSUP">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buscar
                    </button>
                </div>
            </div>
        </form>

        @if ($rutas->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl shadow-md">
                <p class="text-gray-500 text-lg">No encontramos rutas para este trayecto.</p>
                <p class="text-gray-400 mt-2">Intenta con otros puntos de búsqueda.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($rutas as $ruta)
                    <a href="{{ route('rutas.show', $ruta->id) }}" class="card-gowayki group block">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#E74C3C] transition">{{ $ruta->nombre }}</h3>
                                <span class="w-4 h-4 rounded-full" style="background-color: {{ $ruta->color_linea }}"></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600 mb-3">
                                <span class="font-semibold">{{ $ruta->origen }}</span>
                                <svg class="w-4 h-4 text-[#E74C3C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span class="font-semibold">{{ $ruta->destino }}</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>⏱ {{ $ruta->tiempo_estimado_minutos }} min</span>
                                <span class="font-semibold text-[#E74C3C]">{{ $ruta->costo_formateado }}</span>
                                <span>{{ $ruta->paraderos->count() }} paraderos</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
