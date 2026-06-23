import React, { useState, useMemo } from 'react';
import { useRutaData } from './hooks/useRutaData.js';
import { calcularRutaOptima } from './services/calcularRutaOptima.js';
import ParaderoTimeline from './components/ParaderoTimeline.jsx';
import MapaRealista from './components/MapaRealista.jsx';

function obtenerCoordenadasDesdeDOM() {
  const el = document.getElementById('gowayki-mapa-ruta-root');
  if (!el) return { origenStr: null, destinoStr: null };

  const origenStr = el.getAttribute('data-origen');
  const destinoStr = el.getAttribute('data-destino');
  return { origenStr, destinoStr };
}

export default function MapaRutaApp({ rutaId, modo }) {
  const { ruta, cargando, error } = useRutaData(rutaId);
  const [paraderoActivoId, setParaderoActivoId] = useState(null);

  const { origenStr, destinoStr } = useMemo(() => obtenerCoordenadasDesdeDOM(), []);

  const origenUsuario = useMemo(() => {
    if (!ruta || !origenStr) return null;
    const p = ruta.paraderos.find(par => par.nombre === origenStr);
    return p ? { lat: p.lat, lng: p.lng } : null;
  }, [ruta, origenStr]);

  const destinoUsuario = useMemo(() => {
    if (!ruta || !destinoStr) return null;
    const p = ruta.paraderos.find(par => par.nombre === destinoStr);
    return p ? { lat: p.lat, lng: p.lng } : null;
  }, [ruta, destinoStr]);

  const resultadoTramo = useMemo(() => {
    if (modo !== 'planificar' || !ruta || !origenUsuario || !destinoUsuario) {
      return null;
    }
    return calcularRutaOptima(origenUsuario, destinoUsuario, ruta.paraderos);
  }, [modo, ruta, origenUsuario, destinoUsuario]);

  const paraderosAMostrar = resultadoTramo
    ? resultadoTramo.paraderosTramo
    : ruta?.paraderos ?? [];

  if (cargando) {
    return (
      <div className="flex items-center justify-center h-96">
        <span className="text-red-600 font-semibold animate-pulse">Cargando ruta…</span>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex items-center justify-center h-96 text-red-600">
        {error}
      </div>
    );
  }

  if (!ruta) {
    return (
      <div className="flex items-center justify-center h-96 text-gray-400 italic">
        No se encontró la ruta solicitada.
      </div>
    );
  }

  return (
    <div className="flex flex-col md:flex-row gap-4 h-[600px] bg-white rounded-xl shadow-md overflow-hidden">
      <div className="w-full md:w-1/3 border-r border-gray-200">
        {resultadoTramo && (
          <div className="bg-red-50 border-b border-red-200 p-3 text-sm text-red-800 font-medium">
            {resultadoTramo.mensaje}
          </div>
        )}
        {!resultadoTramo && modo === 'planificar' && (origenStr || destinoStr) && (
          <div className="bg-yellow-50 border-b border-yellow-200 p-3 text-sm text-yellow-800 font-medium">
            No encontramos un tramo de esta ruta cercano a tu origen y destino.
          </div>
        )}
        <ParaderoTimeline
          paraderos={paraderosAMostrar}
          paraderoActivoId={paraderoActivoId}
          onSeleccionarParadero={setParaderoActivoId}
        />
      </div>

      <div className="w-full md:w-2/3">
        <MapaRealista
          paraderos={paraderosAMostrar}
          colorLinea={ruta.colorLinea}
          onParaderoClick={setParaderoActivoId}
        />
      </div>
    </div>
  );
}
