@extends('layouts.app')

@section('title', 'Mi Progreso — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mi Progreso de Exploración</h1>
        <p class="text-gray-600 mb-8">Lleva el registro de los destinos que has visitado en Arequipa.</p>

        @if ($errorCarga ?? false)
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="w-16 h-16 mx-auto text-red-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-gray-500 text-lg font-medium">No pudimos cargar tu progreso.</p>
                <p class="text-gray-400 mt-2">Intenta nuevamente en unos momentos.</p>
                <a href="{{ route('perfil.progreso') }}" class="inline-block mt-6 bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-6 py-2 rounded-lg transition">
                    Reintentar
                </a>
            </div>
        @elseif ($progreso['visitados'] === 0)
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <p class="text-gray-500 text-lg font-medium">¡Aún no has explorado ningún destino!</p>
                <p class="text-gray-400 mt-2">Comienza tu aventura visitando los destinos turísticos de Arequipa.</p>
                <a href="{{ route('destinos.index') }}" class="inline-block mt-6 bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-6 py-3 rounded-lg transition">
                    Explorar destinos
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                <div class="text-center mb-6">
                    <span class="text-5xl font-bold text-[#F83A34]">{{ $progreso['porcentaje'] }}%</span>
                    <p class="text-gray-500 mt-2">de exploración completada</p>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-8 mb-4">
                    <div class="bg-[#F83A34] h-8 rounded-full transition-all duration-700 flex items-center justify-end pr-3 text-white font-bold text-sm" style="width: {{ $progreso['porcentaje'] }}%">
                        {{ $progreso['porcentaje'] }}%
                    </div>
                </div>

                <p class="text-center text-lg text-gray-700">
                    Has visitado <span class="font-bold text-[#F83A34]">{{ $progreso['visitados'] }}</span> de <span class="font-bold">{{ $progreso['total'] }}</span> destinos
                </p>

                @if ($progreso['proximo'])
                    <div class="mt-6 p-6 bg-[#FFF3F2] rounded-xl border border-[#FFD0CC] text-center">
                        <p class="text-gray-700">Próximo destino sugerido:</p>
                        <p class="text-2xl font-bold text-[#F83A34] mt-1">{{ $progreso['proximo']->nombre }}</p>
                        <a href="{{ route('destinos.show', $progreso['proximo']->id) }}" class="inline-block mt-3 bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-6 rounded-lg transition">
                            Ir a explorar
                        </a>
                    </div>
                @elseif ($progreso['total'] > 0 && $progreso['visitados'] === $progreso['total'])
                    <div class="mt-6 p-6 bg-green-50 rounded-xl border border-green-200 text-center">
                        <p class="text-2xl font-bold text-green-600">¡Has visitado todos los destinos!</p>
                        <p class="text-gray-600 mt-2">Eres un verdadero explorador de Arequipa.</p>
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Todos los Destinos</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @php
                    $visitadosIds = $progreso['lugares_visitados']->pluck('destino_id')->toArray();
                @endphp
                @foreach (\App\Models\Destino::where('activo', true)->get() as $destino)
                    <a href="{{ route('destinos.show', $destino->id) }}" class="card-gowayki group block {{ in_array($destino->id, $visitadosIds) ? 'ring-2 ring-green-400' : 'opacity-80 hover:opacity-100' }}">
                        <div class="h-40 bg-cover bg-center rounded-t-xl" style="background-image: url('{{ $destino->imagen_url ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Arequipa_Plaza_de_Armas.jpg/640px-Arequipa_Plaza_de_Armas.jpg' }}');">
                            @if (in_array($destino->id, $visitadosIds))
                                <div class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-br-lg inline-block">✓ Visitado</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 group-hover:text-[#F83A34] transition">{{ $destino->nombre ?? '—' }}</h3>
                            <p class="text-sm text-gray-500">{{ $destino->distrito ?? '—' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($progreso['lugares_visitados']->isNotEmpty())
                <h2 class="text-2xl font-bold text-gray-800 mt-12 mb-6">Mis Visitas</h2>
                <div class="space-y-4">
                    @foreach ($progreso['lugares_visitados'] as $visita)
                        <div class="bg-white rounded-xl shadow-md p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $visita->destino->nombre ?? '—' }}</p>
                                    @if ($visita->fecha_visita)
                                        <p class="text-sm text-gray-500">{{ $visita->fecha_visita instanceof \Carbon\Carbon ? $visita->fecha_visita->format('d/m/Y') : $visita->fecha_visita }}</p>
                                    @endif
                                    @if ($visita->notas)
                                        <p class="text-sm text-gray-600 italic mt-1">{{ $visita->notas }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('perfil.desmarcar', $visita->destino_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Desmarcar</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
@endsection

