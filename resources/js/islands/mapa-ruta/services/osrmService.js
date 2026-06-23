/**
 * Consulta el servidor OSRM público para obtener la geometría real
 * de calles entre una secuencia de coordenadas.
 *
 * IMPORTANTE — Limitación conocida: el servidor demo público de OSRM
 * (router.project-osrm.org) tiene rate-limiting y NO debe usarse en
 * producción. Para producción, despliega tu propia instancia OSRM con
 * datos OSM de Arequipa/Perú, o usa un proveedor pago (Mapbox Directions,
 * GraphHopper). Este servicio está preparado para cambiar de baseUrl
 * sin modificar el resto del código.
 */

const OSRM_BASE_URL = import.meta.env.VITE_OSRM_URL || 'https://router.project-osrm.org';

/**
 * @param {Array<{lat:number, lng:number}>} puntos - secuencia ordenada de waypoints
 * @returns {Promise<{coordenadas: Array<[number,number]>, distanciaMetros: number, duracionSegundos: number}>}
 * @throws {Error} si OSRM no responde o la ruta no puede calcularse
 */
export async function obtenerRutaCalles(puntos) {
  if (!puntos || puntos.length < 2) {
    throw new Error('Se requieren al menos 2 puntos para calcular una ruta.');
  }

  const coordsStr = puntos.map(p => `${p.lng},${p.lat}`).join(';');
  const url = `${OSRM_BASE_URL}/route/v1/driving/${coordsStr}?overview=full&geometries=geojson`;

  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`OSRM respondió con estado ${response.status}`);
  }

  const data = await response.json();

  if (data.code !== 'Ok' || !data.routes?.length) {
    throw new Error('OSRM no pudo calcular una ruta para estos puntos.');
  }

  const ruta = data.routes[0];

  return {
    coordenadas: ruta.geometry.coordinates.map(([lng, lat]) => [lat, lng]),
    distanciaMetros: ruta.distance,
    duracionSegundos: ruta.duration,
  };
}
