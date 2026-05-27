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
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent"
                        placeholder="Nombre o descripción">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Categoría</label>
                    <select name="categoria" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
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
                    <select name="distrito" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent">
                        <option value="">Todos</option>
                        @foreach ($distritos as $dist)
                            <option value="{{ $dist }}" {{ ($filtros['distrito'] ?? '') === $dist ? 'selected' : '' }}>
                                {{ $dist }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        @if ($destinos->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl shadow-md">
                <p class="text-gray-500 text-lg">No se encontraron destinos con los filtros seleccionados.</p>
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
