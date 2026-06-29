@extends('layouts.app')

@section('title', 'Nueva Ruta — GoWayki')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Nueva Ruta</h1>

        <form method="POST" action="{{ route('admin.rutas.store') }}" class="bg-white rounded-xl shadow-md p-8">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Nombre de la ruta</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                    @error('nombre')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">{{ old('descripcion') }}</textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Origen</label>
                    <input type="text" name="origen" value="{{ old('origen') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                    @error('origen')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Destino</label>
                    <input type="text" name="destino" value="{{ old('destino') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                    @error('destino')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tiempo estimado (minutos)</label>
                    <input type="number" name="tiempo_estimado_minutos" value="{{ old('tiempo_estimado_minutos') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                    @error('tiempo_estimado_minutos')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Costo aproximado (S/)</label>
                    <input type="number" step="0.01" name="costo_aproximado_soles" value="{{ old('costo_aproximado_soles') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#F83A34] focus:border-transparent">
                    @error('costo_aproximado_soles')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Color de línea</label>
                    <input type="color" name="color_linea" value="{{ old('color_linea', '#F83A34') }}" class="w-full h-10 px-1 py-1 border rounded-lg cursor-pointer">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="activa" value="1" {{ old('activa', '1') === '1' ? 'checked' : '' }} class="h-5 w-5 text-[#F83A34] focus:ring-[#F83A34] border-gray-300 rounded">
                    <label class="ml-2 text-gray-700">Ruta activa</label>
                </div>
            </div>

            <div class="flex items-center space-x-4 mt-8">
                <button type="submit" class="bg-[#F83A34] hover:bg-[#D82027] text-white font-semibold py-2 px-8 rounded-lg transition">Crear Ruta</button>
                <a href="{{ route('admin.rutas.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">Cancelar</a>
            </div>
        </form>
    </div>
@endsection

