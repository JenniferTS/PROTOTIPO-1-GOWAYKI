/**
 * Calcula el tramo óptimo de paraderos entre un punto de origen y uno de
 * destino dados por el usuario (NO necesariamente paraderos exactos de
 * la ruta, sino coordenadas libres ingresadas/seleccionadas).
 *
 * Estrategia:
 * 1. Encuentra el paradero MÁS CERCANO al origen del usuario ("sube en X").
 * 2. Encuentra el paradero MÁS CERCANO al destino del usuario ("baja en Y").
 * 3. Retorna únicamente el sub-array de paraderos entre esos dos índices
 *    (inclusive), respetando el orden de la ruta original.
 *
 * @param {{lat:number, lng:number}} origenLatLng
 * @param {{lat:number, lng:number}} destinoLatLng
 * @param {Array} todosLosParaderos - lista completa ordenada por `orden`
 * @param {number} [umbralMaximoMetros=800] - distancia máx. aceptable
 *        entre el punto del usuario y el paradero más cercano
 * @returns {{
 *   paraderosTramo: Array,
 *   paraderoSubida: object,
 *   paraderoBajada: object,
 *   distanciaTotalMetros: number,
 *   mensaje: string
 * } | null} null si no se encontró un tramo válido (ningún paradero
 *           dentro del umbral aceptable)
 */
export function calcularRutaOptima(origenLatLng, destinoLatLng, todosLosParaderos, umbralMaximoMetros = 800) {
  if (!todosLosParaderos || todosLosParaderos.length < 2) return null;

  const paraderoSubida = encontrarMasCercano(origenLatLng, todosLosParaderos);
  const paraderoBajada = encontrarMasCercano(destinoLatLng, todosLosParaderos);

  if (!paraderoSubida || !paraderoBajada) return null;
  if (paraderoSubida.distancia > umbralMaximoMetros) return null;
  if (paraderoBajada.distancia > umbralMaximoMetros) return null;

  const ordenSubida = paraderoSubida.paradero.orden;
  const ordenBajada = paraderoBajada.paradero.orden;

  if (ordenSubida >= ordenBajada) {
    return null;
  }

  const paraderosTramo = todosLosParaderos.filter(
    (p) => p.orden >= ordenSubida && p.orden <= ordenBajada
  );

  const distanciaTotalMetros = calcularDistanciaAcumulada(paraderosTramo);

  return {
    paraderosTramo,
    paraderoSubida: paraderoSubida.paradero,
    paraderoBajada: paraderoBajada.paradero,
    distanciaTotalMetros,
    mensaje: `Sube en ${paraderoSubida.paradero.nombre}, baja en ${paraderoBajada.paradero.nombre}.`,
  };
}

function encontrarMasCercano(punto, paraderos) {
  let mejor = null;
  let mejorDistancia = Infinity;

  for (const p of paraderos) {
    const d = distanciaHaversine(punto.lat, punto.lng, p.lat, p.lng);
    if (d < mejorDistancia) {
      mejorDistancia = d;
      mejor = p;
    }
  }

  return mejor ? { paradero: mejor, distancia: mejorDistancia } : null;
}

function distanciaHaversine(lat1, lng1, lat2, lng2) {
  const R = 6371000;
  const rad = (deg) => (deg * Math.PI) / 180;

  const dLat = rad(lat2 - lat1);
  const dLng = rad(lng2 - lng1);

  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLng / 2) ** 2;

  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function calcularDistanciaAcumulada(paraderosTramo) {
  let total = 0;
  for (let i = 0; i < paraderosTramo.length - 1; i++) {
    total += distanciaHaversine(
      paraderosTramo[i].lat, paraderosTramo[i].lng,
      paraderosTramo[i + 1].lat, paraderosTramo[i + 1].lng
    );
  }
  return Math.round(total);
}
