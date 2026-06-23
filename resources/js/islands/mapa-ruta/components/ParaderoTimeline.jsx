import React from 'react';

/**
 * Panel lateral izquierdo: timeline vertical de paraderos.
 * @param {Array} paraderos - lista completa o filtrada (Fase 3) de paraderos
 * @param {number|null} paraderoActivoId - paradero resaltado actualmente
 * @param {Function} onSeleccionarParadero - callback al hacer click en un item
 */
export default function ParaderoTimeline({ paraderos, paraderoActivoId, onSeleccionarParadero }) {
  if (!paraderos || paraderos.length === 0) {
    return (
      <div className="p-6 text-center text-gray-400">
        <p className="italic">No hay paraderos para mostrar en este tramo.</p>
      </div>
    );
  }

  return (
    <div className="h-full overflow-y-auto px-4 py-3" style={{ maxHeight: '600px' }}>
      <h3 className="font-bold text-gray-800 mb-4 uppercase text-sm tracking-wide">
        Paraderos del recorrido
      </h3>

      <ol className="relative border-l-2 border-red-200 ml-2">
        {paraderos.map((p, idx) => {
          const esActivo = p.id === paraderoActivoId;
          return (
            <li
              key={p.id}
              onClick={() => onSeleccionarParadero?.(p.id)}
              className={`mb-4 ml-4 cursor-pointer transition-all duration-200 ${
                esActivo ? 'scale-[1.02]' : ''
              }`}
            >
              <span
                className={`absolute flex items-center justify-center w-6 h-6 rounded-full -left-3
                  ring-4 ring-white transition-colors duration-200
                  ${esActivo ? 'bg-red-600' : 'bg-blue-500'}`}
              >
                <span className="text-white text-xs font-bold">{idx + 1}</span>
              </span>

              <div
                className={`flex items-center gap-3 rounded-lg p-2 transition-shadow duration-200
                  ${esActivo ? 'bg-red-50 shadow-md' : 'bg-white hover:bg-gray-50 shadow-sm'}`}
              >
                <img
                  src={p.fotoUrl}
                  alt={p.nombre}
                  className="w-14 h-14 object-cover rounded-md flex-shrink-0"
                  onError={(e) => { e.currentTarget.src = '/images/paraderos/default.jpg'; }}
                />
                <div>
                  <p className="font-semibold text-gray-800 text-sm leading-tight">
                    {p.nombre}
                  </p>
                  <p className="text-xs text-gray-500">
                    {p.tiempoEstimadoMin} min desde el origen
                  </p>
                </div>
              </div>
            </li>
          );
        })}
      </ol>
    </div>
  );
}
