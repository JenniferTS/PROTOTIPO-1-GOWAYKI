<footer class="bg-[#111827] text-white">
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8">

            <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                <a href="{{ url('/') }}" class="inline-flex items-center">
                    <img
                        src="{{ asset('images/brand/gowayki-logo-white.png') }}"
                        alt="GoWayki"
                        class="h-12 w-auto object-contain"
                    >
                </a>

                <p class="text-sm text-gray-400 mt-3 max-w-xs">
                    Transporte inteligente para Arequipa.
                </p>
            </div>

            <nav class="flex flex-wrap justify-center gap-6 text-sm font-semibold text-gray-300">
                <a href="{{ url('/') }}" class="hover:text-white transition">Inicio</a>
                <a href="{{ url('/rutas') }}" class="hover:text-white transition">Rutas</a>
                <a href="{{ url('/destinos') }}" class="hover:text-white transition">Destinos</a>
                <a href="{{ url('/planificar') }}" class="hover:text-white transition">Planificar</a>
            </nav>

            <div class="text-center lg:text-right">
                <p class="text-sm text-gray-300">
                    © {{ date('Y') }} GoWayki
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Rutas urbanas, destinos y movilidad local.
                </p>
            </div>

        </div>
    </div>
</footer>

