@extends('layouts.app')

@section('title', 'Error — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-16 text-center">
        <svg class="w-20 h-20 mx-auto text-red-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Algo salió mal</h1>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">{{ $mensaje ?? 'No pudimos completar esta acción. Intenta nuevamente en unos minutos.' }}</p>
        <a href="{{ route('rutas.index') }}" class="inline-block bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-8 py-3 rounded-lg transition">
            Volver a rutas
        </a>
    </div>
@endsection

