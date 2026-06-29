@extends('layouts.app')

@section('title', 'Mi Ruta — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mi Ruta</h1>
        <p class="text-gray-600 mb-8">Tus recorridos guardados.</p>

        @if ($recorridos->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-md">
                <p class="text-gray-500 text-lg mb-4">Aún no has guardado ningún recorrido.</p>
                <a href="{{ route('recorridos.planificar') }}" class="bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-3 px-8 rounded-lg transition">
                    Planificar un recorrido
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($recorridos as $recorrido)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $recorrido->nombre }}</h3>
                                <div class="flex items-center space-x-2 text-gray-600 mt-2">
                                    <span class="font-semibold">{{ $recorrido->origen }}</span>
                                    <svg class="w-4 h-4 text-[#F83A34]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    <span class="font-semibold">{{ $recorrido->destino }}</span>
                                </div>
                                @if ($recorrido->ruta)
                                    <p class="text-sm text-gray-500 mt-1">Ruta: {{ $recorrido->ruta->nombre }}</p>
                                @endif
                                @if ($recorrido->notas)
                                    <p class="text-sm text-gray-500 mt-1 italic">{{ $recorrido->notas }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $recorrido->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <form method="POST" action="{{ route('recorridos.destroy', $recorrido) }}" onsubmit="return confirm('¿Eliminar este recorrido?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

