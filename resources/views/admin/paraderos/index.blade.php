@extends('admin.layouts.master')

@section('title', 'Gestionar Paraderos')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
    <p style="color:var(--color-text-muted);font-size:0.9rem;">{{ $paraderos->count() }} paraderos registrados</p>
    <a href="{{ route('admin.paraderos.create') }}" class="btn btn-primary">+ Nuevo paradero</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ruta</th>
                <th>Orden</th>
                <th>Coordenadas</th>
                <th style="width:120px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($paraderos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td><strong>{{ $p->nombre }}</strong></td>
                <td><span class="badge badge-success">{{ $p->ruta->nombre ?? '—' }}</span></td>
                <td>{{ $p->orden }}</td>
                <td style="font-family:monospace;font-size:0.78rem;">
                    {{ number_format($p->latitud, 5) }}, {{ number_format($p->longitud, 5) }}
                </td>
                <td>
                    <a href="{{ route('admin.paraderos.edit', $p) }}" class="btn btn-sm btn-outline">Editar</a>
                    <form method="POST" action="{{ route('admin.paraderos.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar este paradero?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><p>No hay paraderos registrados.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
