@extends('layouts.app')

@section('title', 'Administrar Rutas — GoWayki')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Administrar Rutas</h1>
                <p class="text-gray-600">Gestión de rutas de transporte.</p>
            </div>
            <a href="{{ route('admin.rutas.create') }}" class="bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-6 rounded-lg transition">
                Nueva Ruta
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nombre</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Origen</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Destino</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tiempo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Costo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Activa</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($rutas as $ruta)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $ruta->nombre }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruta->origen }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruta->destino }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruta->tiempo_estimado_minutos }} min</td>
                            <td class="px-6 py-4 text-gray-600">{{ $ruta->costo_formateado }}</td>
                            <td class="px-6 py-4">
                                @if ($ruta->activa)
                                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Sí</span>
                                @else
                                    <span class="bg-[#FFE7E5] text-red-700 text-xs font-semibold px-2 py-1 rounded-full">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.rutas.edit', $ruta) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Editar</a>
                                <form method="POST" action="{{ route('admin.rutas.destroy', $ruta) }}" class="inline" onsubmit="return confirm('¿Eliminar esta ruta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[#F83A34] hover:text-[#D82027] font-medium text-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

