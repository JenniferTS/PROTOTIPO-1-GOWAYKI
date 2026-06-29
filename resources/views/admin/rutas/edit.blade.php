@extends('admin.layouts.master')

@section('title', 'Editar Ruta')

@section('content')
<div class="card" style="padding:24px;max-width:720px;">
    <form method="POST" action="{{ route('admin.rutas.update', $ruta) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre de la ruta</label>
            <input class="form-control" name="nombre" value="{{ old('nombre', $ruta->nombre) }}" required>
            @error('nombre')<small style="color:var(--danger);">{{ $message }}</small>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group">
                <label>Origen</label>
                <input class="form-control" name="origen" value="{{ old('origen', $ruta->origen) }}" required>
                @error('origen')<small style="color:var(--danger);">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label>Destino</label>
                <input class="form-control" name="destino" value="{{ old('destino', $ruta->destino) }}" required>
                @error('destino')<small style="color:var(--danger);">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea class="form-control" name="descripcion" rows="2">{{ old('descripcion', $ruta->descripcion) }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">
            <div class="form-group">
                <label>Tiempo estimado (min)</label>
                <input class="form-control" type="number" name="tiempo_estimado_minutos" value="{{ old('tiempo_estimado_minutos', $ruta->tiempo_estimado_minutos) }}" min="0">
            </div>
            <div class="form-group">
                <label>Costo (S/)</label>
                <input class="form-control" type="number" step="0.10" name="costo_aproximado_soles" value="{{ old('costo_aproximado_soles', $ruta->costo_aproximado_soles) }}" min="0">
            </div>
            <div class="form-group">
                <label>Color de línea</label>
                <input class="form-control" type="color" name="color_linea" value="{{ old('color_linea', $ruta->color_linea) }}">
            </div>
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:8px;">
            <input type="checkbox" name="activa" value="1" id="activa" {{ old('activa', $ruta->activa) ? 'checked' : '' }}>
            <label for="activa" style="margin:0;">Ruta activa</label>
        </div>

        <div class="modal-footer" style="padding:16px 0 0 0;border-top:none;">
            <a href="{{ route('admin.rutas.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Ruta</button>
        </div>
    </form>
</div>
@endsection
