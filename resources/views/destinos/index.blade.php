@extends('layouts.app')

@section('title', 'Destinos — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Destinos Turísticos</h1>
        <p class="text-gray-600 mb-8">Descubre los mejores lugares para visitar en Arequipa.</p>

        <form method="GET" action="{{ route('destinos.index') }}" class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Buscar</label>
                    <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent"
                        placeholder="Nombre o descripción">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Categoría</label>
                    <select name="categoria" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                        <option value="">Todas</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat }}" {{ ($filtros['categoria'] ?? '') === $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Distrito</label>
                    <select name="distrito" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                        <option value="">Todos</option>
                        @foreach ($distritos as $dist)
                            <option value="{{ $dist }}" {{ ($filtros['distrito'] ?? '') === $dist ? 'selected' : '' }}>
                                {{ $dist }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        @if ($destinos->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                @if (($filtros['q'] ?? '') !== '')
                    <p class="text-gray-500 text-lg font-medium">No encontramos destinos que coincidan con '<span class="font-semibold">{{ $filtros['q'] }}</span>'.</p>
                    <p class="text-gray-400 mt-2">Prueba con otro término de búsqueda.</p>
                @elseif (($filtros['categoria'] ?? '') !== '' || ($filtros['distrito'] ?? '') !== '')
                    <p class="text-gray-500 text-lg font-medium">No hay destinos disponibles con los filtros seleccionados.</p>
                    <p class="text-gray-400 mt-2">Intenta con otras categorías o distritos.</p>
                @else
                    <p class="text-gray-500 text-lg font-medium">Aún no hay destinos disponibles.</p>
                    <p class="text-gray-400 mt-2">Vuelve más tarde o explora las rutas de transporte.</p>
                @endif
                @if (($filtros['q'] ?? '') !== '' || ($filtros['categoria'] ?? '') !== '' || ($filtros['distrito'] ?? '') !== '')
                    <div class="mt-6">
                        <a href="{{ route('destinos.index') }}" class="inline-block bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-6 py-2 rounded-lg transition">
                            Ver todos
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($destinos as $destino)
                    @include('partials.destino-card', ['destino' => $destino])
                @endforeach
            </div>
            <div class="mt-8">
                {{ $destinos->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

