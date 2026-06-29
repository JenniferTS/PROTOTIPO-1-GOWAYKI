@extends('layouts.app')

@section('title', ($ruta->nombre ?? 'Ruta') . ' — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center space-x-2 text-gray-500 mb-4">
            <a href="{{ route('rutas.index') }}" class="hover:text-[#F83A34]">Rutas</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">{{ $ruta->nombre }}</span>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <span class="w-5 h-5 rounded-full" style="background-color: {{ $ruta->color_linea ?? '#F83A34' }}"></span>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $ruta->nombre }}</h1>
                </div>

                <p class="text-gray-600 mb-6">{{ $ruta->descripcion ?? 'Sin descripción disponible.' }}</p>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#F83A34]">{{ $ruta->tiempo_estimado_minutos ?? '—' }}</p>
                        <p class="text-gray-500 text-sm">Minutos</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#F83A34]">{{ $ruta->costo_formateado ?? 'S/ 0.00' }}</p>
                        <p class="text-gray-500 text-sm">Tarifa base</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#F83A34]">{{ $ruta->paraderos->count() }}</p>
                        <p class="text-gray-500 text-sm">Paraderos</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Recorrido</h2>
                    <div class="flex items-center mb-4">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div class="w-0.5 h-16 bg-gray-300"></div>
                            <div class="w-3 h-3 bg-[#FFF3F2]0 rounded-full"></div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $ruta->origen ?? '—' }}</p>
                            <p class="text-gray-400 text-sm my-4">Inicio del recorrido</p>
                            <p class="font-semibold text-gray-800">{{ $ruta->destino ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Paraderos</h2>
                    @if ($ruta->paraderos->isEmpty())
                        <p class="text-gray-500 italic">Aún no se han registrado paraderos para esta ruta.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($ruta->paraderos as $paradero)
                                <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-xl border border-gray-100 transition">
                                    <div class="relative w-24 h-20 rounded-xl overflow-hidden bg-gray-200 flex-shrink-0">
                                        @if (!empty($paradero->imagen_url))
                                            <img src="{{ $paradero->imagen_url }}"
                                                 alt="{{ $paradero->nombre ?? 'Paradero' }}"
                                                 class="w-full h-full object-cover"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs text-center px-2">
                                                Sin imagen
                                            </div>
                                        @endif
                                        <span class="absolute top-2 left-2 w-7 h-7 bg-[#F83A34] text-white rounded-full flex items-center justify-center font-bold text-xs shadow">
                                            {{ $paradero->orden ?? '?' }}
                                        </span>
                                    </div>

                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800">{{ $paradero->nombre ?? 'Paradero sin nombre' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Punto {{ $paradero->orden ?? '?' }} del recorrido
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div
                    id="gowayki-mapa-ruta-root"
                    data-ruta-id="{{ $ruta->id }}"
                    data-modo="detalle"
                    style="width: 100%; min-height: 600px;"
                ></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite('resources/js/islands/mapa-ruta/index.jsx')
@endpush


