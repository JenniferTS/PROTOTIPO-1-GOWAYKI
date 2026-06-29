<a href="{{ route('destinos.show', $destino->id) }}" class="card-gowayki group block">
    <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $destino->imagen_url ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ab/Arequipa_Plaza_de_Armas.jpg/640px-Arequipa_Plaza_de_Armas.jpg' }}');">
    </div>
    <div class="p-4">
        <h3 class="font-bold text-lg text-gray-800 group-hover:text-[#F83A34] transition">{{ $destino->nombre }}</h3>
        <p class="text-sm text-gray-500">{{ $destino->distrito }}</p>
        <div class="flex items-center justify-between mt-2">
            <span class="inline-block bg-[#FFE7E5] text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                {{ ucfirst($destino->categoria) }}
            </span>
            <span class="text-yellow-500 text-sm font-bold">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($destino->calificacion))
                        ★
                    @elseif ($i - 0.5 <= $destino->calificacion)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
                {{ number_format($destino->calificacion, 1) }}
            </span>
        </div>
    </div>
</a>

