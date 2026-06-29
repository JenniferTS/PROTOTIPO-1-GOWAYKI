import React from 'react';

const obtenerImagen = (paradero) => {
  return paradero.imagen_url || paradero.imagenUrl || paradero.fotoUrl || null;
};

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
          const imagen = obtenerImagen(p);

          return (
            <li
              key={p.id}
              onClick={() => onSeleccionarParadero?.(p.id)}
              className={`mb-4 ml-4 cursor-pointer transition-all duration-200 ${
                esActivo ? 'scale-[1.02]' : ''
              }`}
            >
              <span
                className={`absolute flex items-center justify-center w-7 h-7 rounded-full -left-3.5
                  ring-4 ring-white transition-colors duration-200 shadow-sm
                  ${esActivo ? 'bg-red-600' : 'bg-blue-500'}`}
              >
                <span className="text-white text-xs font-bold">{idx + 1}</span>
              </span>

              <div
                className={`flex items-center gap-3 rounded-xl p-2 transition-shadow duration-200 border
                  ${esActivo ? 'bg-red-50 border-red-100 shadow-md' : 'bg-white border-gray-100 hover:bg-gray-50 shadow-sm'}`}
              >
                <div className="w-16 h-16 rounded-lg overflow-hidden bg-gradient-to-br from-red-50 to-blue-50 flex-shrink-0 border border-gray-100">
                  {imagen ? (
                    <img
                      src={imagen}
                      alt={p.nombre}
                      className="w-full h-full object-cover"
                      loading="lazy"
                      onError={(e) => {
                        e.currentTarget.style.display = 'none';
                        const fallback = e.currentTarget.nextElementSibling;
                        if (fallback) fallback.style.display = 'flex';
                      }}
                    />
                  ) : null}

                  <div
                    className="w-full h-full items-center justify-center text-center px-2"
                    style={{ display: imagen ? 'none' : 'flex' }}
                  >
                    <div>
                      <div className="mx-auto w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mb-1">
                        <span className="text-red-600 text-lg">📍</span>
                      </div>
                      <p className="text-[10px] text-gray-500 font-semibold leading-tight">
                        Paradero
                      </p>
                    </div>
                  </div>
                </div>

                <div className="min-w-0 flex-1">
                  <p className="font-bold text-gray-800 text-sm leading-tight">
                    {p.nombre}
                  </p>
                  <p className="text-xs text-gray-500 mt-1">
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
