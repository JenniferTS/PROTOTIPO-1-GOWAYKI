import { useState, useEffect } from 'react';

/**
 * Carga los datos de una ruta. En producción esto debe reemplazarse
 * por un fetch a /api/rutas/{id}/paraderos (endpoint Laravel existente).
 * Por ahora consume el mock JSON de la Fase 1.
 */
export function useRutaData(rutaId) {
  const [ruta, setRuta]       = useState(null);
  const [cargando, setCargando] = useState(true);
  const [error, setError]     = useState(null);

  useEffect(() => {
    setCargando(true);
    import('../data/rutaMercadoTecsup.json')
      .then((mod) => {
        setRuta(mod.default);
        setError(null);
      })
      .catch((err) => {
        console.error('Error cargando datos de ruta:', err);
        setError('No pudimos cargar la información de la ruta.');
      })
      .finally(() => setCargando(false));
  }, [rutaId]);

  return { ruta, cargando, error };
}
