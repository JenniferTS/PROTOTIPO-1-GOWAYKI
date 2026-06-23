import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles.css';
import MapaRutaApp from './MapaRutaApp.jsx';

const mountPoint = document.getElementById('gowayki-mapa-ruta-root');

if (mountPoint) {
  const rutaId = mountPoint.dataset.rutaId;
  const modo   = mountPoint.dataset.modo;
  const root   = createRoot(mountPoint);
  root.render(<MapaRutaApp rutaId={rutaId} modo={modo} />);
}
