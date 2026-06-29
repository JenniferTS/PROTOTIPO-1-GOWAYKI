@extends('admin.layouts.master')

@section('title', 'Gestionar Usuarios')

@section('content')
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Notificaciones</th>
                <th>Registro</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($usuarios as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td><strong>{{ $u->name }}</strong></td>
                <td>{{ $u->email }}</td>
                <td><span class="badge {{ $u->role === 'admin' ? 'badge-warning' : 'badge-success' }}">{{ $u->role }}</span></td>
                <td>{{ $u->notificaciones_activas ? 'Sí' : 'No' }}</td>
                <td style="font-size:0.78rem;color:var(--color-text-muted);">{{ $u->created_at }}</td>
            </tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><p>No hay usuarios registrados.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
