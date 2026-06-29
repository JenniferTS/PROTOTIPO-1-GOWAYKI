@extends('admin.layouts.master')

@section('title', 'Editar Paradero')

@push('styles')
<style>
    #mapaParadero { width: 100%; height: 380px; border-radius: 12px; border: 1.5px solid var(--border); }
</style>
@endpush

@section('content')
<div class="card" style="padding:24px;max-width:720px;">
    <form method="POST" action="{{ route('admin.paraderos.update', $paradero) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre del paradero</label>
            <input class="form-control" name="nombre" value="{{ old('nombre', $paradero->nombre) }}" required>
            @error('nombre')<small style="color:var(--danger);">{{ $message }}</small>@enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group">
                <label>Ruta</label>
                <select class="form-control" name="ruta_id" required>
                    <option value="">— Seleccionar —</option>
                    @foreach ($rutas as $r)
                    <option value="{{ $r->id }}" {{ old('ruta_id', $paradero->ruta_id) == $r->id ? 'selected' : '' }}>{{ $r->nombre }} ({{ $r->origen }} → {{ $r->destino }})</option>
                    @endforeach
                </select>
                @error('ruta_id')<small style="color:var(--danger);">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label>Orden</label>
                <input class="form-control" type="number" name="orden" value="{{ old('orden', $paradero->orden) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Referencia (opcional)</label>
            <input class="form-control" name="referencia" value="{{ old('referencia', $paradero->referencia) }}" placeholder="Ej: Frente al mercado">
        </div>

        <input type="hidden" name="latitud" id="f_latitud" value="{{ old('latitud', $paradero->latitud) }}">
        <input type="hidden" name="longitud" id="f_longitud" value="{{ old('longitud', $paradero->longitud) }}">

        <div class="form-group">
            <label>Ubicación — Arrastra el marcador</label>
            <div id="mapaParadero"></div>
            <p style="font-size:0.78rem;color:var(--color-text-muted);margin-top:6px;">
                Coordenadas: <span id="pCoordsLabel">{{ number_format($paradero->latitud, 5) }}, {{ number_format($paradero->longitud, 5) }}</span>
            </p>
        </div>

        <div class="modal-footer" style="padding:16px 0 0 0;border-top:none;">
            <a href="{{ route('admin.paraderos.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Paradero</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = parseFloat(document.getElementById('f_latitud').value) || -16.409;
    const lng = parseFloat(document.getElementById('f_longitud').value) || -71.537;

    const mapa = L.map('mapaParadero', { center: [lat, lng], zoom: 16 });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapa);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(mapa);

    function actualizarCoords(latlng) {
        document.getElementById('f_latitud').value = latlng.lat.toFixed(6);
        document.getElementById('f_longitud').value = latlng.lng.toFixed(6);
        document.getElementById('pCoordsLabel').textContent = latlng.lat.toFixed(5) + ', ' + latlng.lng.toFixed(5);
    }

    marker.on('dragend', function () { actualizarCoords(marker.getLatLng()); });
    mapa.on('click', function (e) { marker.setLatLng(e.latlng); actualizarCoords(e.latlng); });
});
</script>
@endpush
