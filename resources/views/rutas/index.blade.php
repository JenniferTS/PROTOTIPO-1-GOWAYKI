@extends('layouts.app')

@section('title', 'Rutas de Transporte — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Rutas de Transporte</h1>
        <p class="text-gray-600 mb-8">Encuentra la mejor ruta de combi o bus para moverte por Arequipa.</p>

        @if ($degradado ?? false)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-center space-x-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Mostrando información en caché. Algunos datos podrían no estar actualizados.</span>
            </div>
        @endif

        <form method="GET" action="{{ route('rutas.index') }}" class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="origen">Punto de partida</label>
                    <input type="text" id="origen" name="origen" value="{{ $request->origen ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent @error('origen') border-red-500 @enderror"
                        placeholder="Ej: Plaza de Armas" required minlength="2">
                    @error('origen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="destino">Destino</label>
                    <input type="text" id="destino" name="destino" value="{{ $request->destino ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent @error('destino') border-red-500 @enderror"
                        placeholder="Ej: TECSUP" required minlength="2">
                    @error('destino')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Buscar
                    </button>
                </div>
            </div>
        </form>

        @if ($errors->any() && !$errors->has('origen') && !$errors->has('destino'))
            <div class="bg-[#FFE7E5] border-l-4 border-red-500 text-[#D82027] px-4 py-3 rounded-lg mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($rutas->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                @if ($request->filled('origen') || $request->filled('destino'))
                    <p class="text-gray-500 text-lg font-medium">No encontramos rutas disponibles entre <span class="font-semibold">{{ $request->origen }}</span> y <span class="font-semibold">{{ $request->destino }}</span>.</p>
                    <p class="text-gray-400 mt-2">Intenta con otros puntos de búsqueda o explora todas las rutas.</p>
                @else
                    <p class="text-gray-500 text-lg font-medium">No hay rutas disponibles en este momento.</p>
                    <p class="text-gray-400 mt-2">Vuelve más tarde o explora destinos turísticos.</p>
                @endif
                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('rutas.index') }}" class="bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-6 py-2 rounded-lg transition">
                        Ver todas las rutas
                    </a>
                    <a href="{{ route('destinos.index') }}" class="border border-[#F83A34] text-[#F83A34] hover:bg-[#F83A34] hover:text-white font-semibold px-6 py-2 rounded-lg transition">
                        Explorar destinos
                    </a>
                </div>
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($rutas as $ruta)
                    <a href="{{ route('rutas.show', $ruta->id) }}" class="card-gowayki group block">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#F83A34] transition">{{ $ruta->nombre }}</h3>
                                <span class="w-4 h-4 rounded-full" style="background-color: {{ $ruta->color_linea ?? '#F83A34' }}"></span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600 mb-3">
                                <span class="font-semibold">{{ $ruta->origen }}</span>
                                <svg class="w-4 h-4 text-[#F83A34]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <span class="font-semibold">{{ $ruta->destino }}</span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>⏱ {{ $ruta->tiempo_estimado_minutos ?? '—' }} min</span>
                                <span class="font-semibold text-[#F83A34]">{{ $ruta->costo_formateado ?? 'S/ 0.00' }}</span>
                                <span>{{ $ruta->paraderos->count() }} paraderos</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection

