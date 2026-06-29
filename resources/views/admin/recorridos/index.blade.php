@extends('admin.layouts.master')

@section('title', 'Recorridos de Usuarios')

@section('content')
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($recorridos as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->user->name ?? '—' }}</td>
                <td><strong>{{ $r->nombre }}</strong></td>
                <td>{{ $r->origen }}</td>
                <td>{{ $r->destino }}</td>
                <td style="font-size:0.78rem;color:var(--color-text-muted);">{{ $r->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><p>No hay recorridos guardados aún.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
