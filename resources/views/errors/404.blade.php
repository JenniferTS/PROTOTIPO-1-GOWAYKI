@extends('layouts.app')

@section('title', 'Página no encontrada — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-16 text-center">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 17.172a4 4 0 015.656 0M12 14a2 2 0 100-4 2 2 0 000 4zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-2">Página no encontrada</p>
        <p class="text-gray-500 mb-8">La página que buscas no existe o ha sido movida.</p>
        <a href="{{ route('home') }}" class="inline-block bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold px-8 py-3 rounded-lg transition">
            Volver al inicio
        </a>
    </div>
@endsection

