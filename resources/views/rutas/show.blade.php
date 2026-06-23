@extends('layouts.app')

@section('title', ($ruta->nombre ?? 'Ruta') . ' — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center space-x-2 text-gray-500 mb-4">
            <a href="{{ route('rutas.index') }}" class="hover:text-[#E74C3C]">Rutas</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">{{ $ruta->nombre }}</span>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <span class="w-5 h-5 rounded-full" style="background-color: {{ $ruta->color_linea ?? '#E74C3C' }}"></span>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $ruta->nombre }}</h1>
                </div>

                <p class="text-gray-600 mb-6">{{ $ruta->descripcion ?? 'Sin descripción disponible.' }}</p>

                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#E74C3C]">{{ $ruta->tiempo_estimado_minutos ?? '—' }}</p>
                        <p class="text-gray-500 text-sm">Minutos</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#E74C3C]">{{ $ruta->costo_formateado ?? 'S/ 0.00' }}</p>
                        <p class="text-gray-500 text-sm">Tarifa base</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-4 text-center">
                        <p class="text-2xl font-bold text-[#E74C3C]">{{ $ruta->paraderos->count() }}</p>
                        <p class="text-gray-500 text-sm">Paraderos</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Recorrido</h2>
                    <div class="flex items-center mb-4">
                        <div class="flex flex-col items-center mr-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div class="w-0.5 h-16 bg-gray-300"></div>
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
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
                                <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                                    <span class="w-8 h-8 bg-[#FADBD8] text-[#E74C3C] rounded-full flex items-center justify-center font-bold text-sm">{{ $paradero->orden ?? '?' }}</span>
                                    <span class="text-gray-700">{{ $paradero->nombre ?? 'Paradero sin nombre' }}</span>
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
