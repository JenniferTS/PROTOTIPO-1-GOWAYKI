import React, { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Polyline, Marker, Popup, Tooltip } from 'react-leaflet';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { obtenerRutaCalles } from '../services/osrmService.js';

const ICONO_PARADERO = new L.DivIcon({
  className: 'gowayki-marker',
  html: `<div style="
    background:#ffffff;
    border:3px solid #E74C3C;
    border-radius:50%;
    width:18px;height:18px;
    box-shadow:0 1px 4px rgba(0,0,0,0.4);
  "></div>`,
  iconSize: [18, 18],
  iconAnchor: [9, 9],
});

export default function MapaRealista({ paraderos, colorLinea = '#3498DB', onParaderoClick }) {
  const [trazado, setTrazado]   = useState(null);
  const [cargandoRuta, setCargandoRuta] = useState(true);
  const [errorRuta, setErrorRuta]       = useState(null);

  useEffect(() => {
    if (!paraderos || paraderos.length < 2) return;

    setCargandoRuta(true);
    setErrorRuta(null);

    const puntos = paraderos.map(p => ({ lat: p.lat, lng: p.lng }));

    obtenerRutaCalles(puntos)
      .then((resultado) => setTrazado(resultado.coordenadas))
      .catch((err) => {
        console.error('Fallo OSRM, usando fallback de línea recta:', err);
        setTrazado(puntos.map(p => [p.lat, p.lng]));
        setErrorRuta('No pudimos calcular el trazado exacto de calles. Mostrando trayecto aproximado.');
      })
      .finally(() => setCargandoRuta(false));
  }, [paraderos]);

  if (!paraderos || paraderos.length === 0) {
    return (
      <div className="flex items-center justify-center h-full text-gray-400 italic">
        No hay paraderos para mostrar en el mapa.
      </div>
    );
  }

  const centro = [paraderos[0].lat, paraderos[0].lng];

  return (
    <div className="relative w-full h-full">
      {cargandoRuta && (
        <div className="absolute inset-0 z-30 flex items-center justify-center bg-white/60">
          <span className="text-red-600 font-semibold animate-pulse">Calculando trazado real…</span>
        </div>
      )}

      {errorRuta && !cargandoRuta && (
        <div className="absolute top-2 left-1/2 -translate-x-1/2 z-30 bg-yellow-100 border
                         border-yellow-400 text-yellow-800 text-xs px-3 py-1 rounded-md shadow">
          {errorRuta}
        </div>
      )}

      <MapContainer center={centro} zoom={14} style={{ height: '100%', width: '100%' }}>
        <TileLayer
          url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
          attribution='&copy; OpenStreetMap contributors'
        />

        {trazado && (
          <Polyline
            positions={trazado}
            pathOptions={{ color: colorLinea, weight: 5, opacity: 0.85 }}
          />
        )}

        {paraderos.map((p) => (
          <Marker
            key={p.id}
            position={[p.lat, p.lng]}
            icon={ICONO_PARADERO}
            eventHandlers={{ click: () => onParaderoClick?.(p.id) }}
          >
            <Tooltip direction="top" offset={[0, -10]}>
              <strong>{p.nombre}</strong>
            </Tooltip>

            <Popup minWidth={180}>
              <div className="text-center">
                <img
                  src={p.fotoUrl}
                  alt={p.nombre}
                  className="w-full h-20 object-cover rounded mb-2"
                  onError={(e) => { e.currentTarget.src = '/images/paraderos/default.svg'; }}
                />
                <p className="font-bold text-sm">{p.nombre}</p>
                <p className="text-xs text-gray-500">{p.tiempoEstimadoMin} min desde el origen</p>
              </div>
            </Popup>
          </Marker>
        ))}
      </MapContainer>
    </div>
  );
}
