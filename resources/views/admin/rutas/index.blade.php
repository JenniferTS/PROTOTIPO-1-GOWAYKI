@extends('admin.layouts.master')

@section('title', 'Gestionar Rutas')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <p style="color:var(--color-text-muted);font-size:0.9rem;">{{ $rutas->count() }} rutas registradas</p>
    <a href="{{ route('admin.rutas.create') }}" class="btn btn-primary">+ Nueva ruta</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Costo</th>
                <th>Tiempo</th>
                <th>Activa</th>
                <th style="width:150px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($rutas as $ruta)
            <tr>
                <td>{{ $ruta->id }}</td>
                <td><strong>{{ $ruta->nombre }}</strong></td>
                <td>{{ $ruta->origen }}</td>
                <td>{{ $ruta->destino }}</td>
                <td>{{ $ruta->costo_formateado }}</td>
                <td>{{ $ruta->tiempo_estimado_minutos }} min</td>
                <td>
                    <span class="badge {{ $ruta->activa ? 'badge-success' : 'badge-danger' }}">
                        {{ $ruta->activa ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.rutas.edit', $ruta) }}" class="btn btn-sm btn-outline">Editar</a>
                    <form method="POST" action="{{ route('admin.rutas.destroy', $ruta) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar esta ruta?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8"><div class="empty-state"><p>No hay rutas registradas. Crea la primera.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
