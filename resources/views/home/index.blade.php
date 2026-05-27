@extends('layouts.app')

@section('title', 'GoWayki — Transporte inteligente en Arequipa')

@push('styles')
<style>
#hero-slider .slide {
  will-change: opacity;
}
#hero-slider .thumb-item > div:hover img {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}
#hero-slider a[href*="planificar"] {
  line-height: 1.2;
  text-align: center;
}
@media (max-width: 380px) {
  #slider-thumbnails { display: none; }
}
</style>
@endpush

@section('content')
{{-- HERO SLIDER --}}
<section id="hero-slider"
         class="relative w-full overflow-hidden"
         style="height: 100vh; min-height: 500px;">

  @foreach($sliderDestinos as $index => $destino)
  <div class="slide absolute inset-0 transition-opacity duration-700 ease-in-out"
       data-index="{{ $index }}"
       style="opacity: {{ $index === 0 ? '1' : '0' }}; z-index: {{ $index === 0 ? '10' : '1' }};">

    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ $destino->imagen_url ?? asset('images/destinos/default.svg') }}');">
    </div>

    <div class="absolute inset-0 bg-black" style="opacity: 0.45;"></div>

    <div class="absolute text-white"
         style="top: 18%; left: 5%; max-width: 55%;">
      <p class="font-bold tracking-widest mb-2"
         style="font-size: clamp(0.85rem, 1.5vw, 1rem); letter-spacing: 0.15em;">
        {{ strtoupper($destino->nombre) }}
      </p>
      <h2 class="font-bold leading-tight mb-4"
          style="font-size: clamp(2rem, 5vw, 3.2rem); text-shadow: 0 2px 8px rgba(0,0,0,0.5);">
        {{ $destino->tagline ?? ucfirst($destino->categoria) . ' en Arequipa' }}
      </h2>
      <p class="leading-relaxed"
         style="font-size: clamp(0.9rem, 1.8vw, 1.05rem); max-width: 90%; text-shadow: 0 1px 4px rgba(0,0,0,0.6);">
        {{ \Illuminate\Support\Str::limit($destino->descripcion, 200) }}
      </p>
    </div>

    <div class="absolute" style="bottom: 15%; left: 5%;">
      <a href="{{ route('recorridos.planificar') }}"
         class="inline-block border-2 border-white text-white font-bold uppercase tracking-widest
                hover:bg-white hover:text-gray-900 transition-all duration-200"
         style="padding: 0.75rem 2rem; font-size: 0.85rem; letter-spacing: 0.12em;">
        BUSCAR<br>RUTA
      </a>
    </div>

  </div>
  @endforeach

  {{-- CONTROLES INFERIORES CENTRADOS --}}
  <div class="absolute flex items-center gap-4 z-20"
       style="bottom: 5%; left: 50%; transform: translateX(-50%);">
    <button id="slider-dot"
            class="rounded-full bg-white"
            style="width: 14px; height: 14px; opacity: 0.9; cursor: default;"
            aria-label="Slide actual">
    </button>
    <button id="slider-next"
            class="flex items-center justify-center rounded-full bg-white text-gray-800
                   hover:bg-gray-100 transition-colors duration-200 font-bold"
            style="width: 38px; height: 38px; font-size: 1.1rem;"
            aria-label="Siguiente destino">
      ›
    </button>
  </div>

  {{-- THUMBNAILS INFERIORES DERECHA --}}
  <div id="slider-thumbnails"
       class="absolute flex flex-col gap-2 z-20"
       style="bottom: 4%; right: 2%;">
    @foreach($sliderDestinos as $i => $d)
    <div class="thumb-item hidden" data-dest-index="{{ $i }}">
      <div class="relative overflow-hidden cursor-pointer"
           style="width: 90px; height: 65px; border-radius: 6px;"
           onclick="sliderGoTo({{ $i }})">
        <img src="{{ $d->imagen_url ?? asset('images/destinos/default.svg') }}"
             alt="{{ $d->nombre }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black" style="opacity:0.3;"></div>
        <p class="absolute bottom-0 left-0 right-0 text-white text-center font-bold uppercase
                   leading-tight px-1 pb-1"
           style="font-size: 0.5rem; letter-spacing: 0.05em; text-shadow: 0 1px 3px rgba(0,0,0,0.8);">
          {{ strtoupper($d->nombre) }}
        </p>
      </div>
    </div>
    @endforeach
  </div>

</section>

<script>
(function () {
  const TOTAL      = {{ $sliderDestinos->count() }};
  const INTERVAL   = 5000;
  const THUMBS_NUM = 3;

  let current  = 0;
  let timer    = null;

  const slides     = document.querySelectorAll('#hero-slider .slide');
  const thumbItems = document.querySelectorAll('#hero-slider .thumb-item');
  const nextBtn    = document.getElementById('slider-next');

  function goTo(index) {
    slides[current].style.opacity = '0';
    slides[current].style.zIndex  = '1';

    current = ((index % TOTAL) + TOTAL) % TOTAL;

    slides[current].style.opacity = '1';
    slides[current].style.zIndex  = '10';

    updateThumbnails();
    resetTimer();
  }

  window.sliderGoTo = goTo;

  function next() { goTo(current + 1); }

  function updateThumbnails() {
    thumbItems.forEach(t => t.classList.add('hidden'));
    for (let i = 0; i < THUMBS_NUM; i++) {
      const nextIndex = (current + 1 + i) % TOTAL;
      const thumb = document.querySelector(
        `#hero-slider .thumb-item[data-dest-index="${nextIndex}"]`
      );
      if (thumb) thumb.classList.remove('hidden');
    }
  }

  function resetTimer() {
    if (timer) clearInterval(timer);
    timer = setInterval(next, INTERVAL);
  }

  slides.forEach((slide, i) => {
    slide.style.opacity    = i === 0 ? '1' : '0';
    slide.style.zIndex     = i === 0 ? '10' : '1';
    slide.style.transition = 'opacity 0.7s ease-in-out';
  });

  updateThumbnails();

  nextBtn.addEventListener('click', next);

  const hero = document.getElementById('hero-slider');
  hero.addEventListener('mouseenter', () => { if (timer) clearInterval(timer); });
  hero.addEventListener('mouseleave', resetTimer);

  let touchStartX = 0;
  hero.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
  hero.addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      diff > 0 ? next() : goTo(current - 1);
    }
  }, { passive: true });

  resetTimer();
})();
</script>

{{-- Progreso del usuario --}}
<section class="relative z-10">
  @auth
    @if ($progreso)
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-md p-6 -mt-16 relative z-20">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Mi Progreso de Exploración</h2>
          <div class="w-full bg-gray-200 rounded-full h-6 mb-2">
            <div class="bg-[#E74C3C] h-6 rounded-full transition-all duration-500 flex items-center justify-end pr-2 text-xs text-white font-bold" style="width: {{ $progreso['porcentaje'] }}%">
              {{ $progreso['porcentaje'] }}%
            </div>
          </div>
          <p class="text-gray-600">{{ $progreso['visitados'] }} de {{ $progreso['total'] }} destinos visitados</p>
          @if ($progreso['proximo'])
            <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
              <p class="font-semibold text-gray-700">Próximo destino sugerido:</p>
              <p class="text-lg font-bold text-[#E74C3C]">{{ $progreso['proximo']->nombre }}</p>
              <a href="{{ route('destinos.show', $progreso['proximo']->id) }}" class="inline-block mt-2 bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-2 px-4 rounded-lg text-sm transition">Ir a explorar</a>
            </div>
          @endif
        </div>
      </div>
    @endif
  @endauth
</section>

{{-- Destinos destacados --}}
<section class="max-w-7xl mx-auto px-4 py-12">
  <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Destinos Destacados</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach ($destinos as $destino)
      @include('partials.destino-card', ['destino' => $destino])
    @endforeach
  </div>
  <div class="text-center mt-8">
    <a href="{{ route('destinos.index') }}" class="bg-[#E74C3C] hover:bg-[#C0392B] text-white font-semibold py-3 px-8 rounded-lg transition">Ver Todos los Destinos</a>
  </div>
</section>

{{-- Cómo funciona --}}
<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">¿Cómo funciona?</h2>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-[#E74C3C] rounded-full flex items-center justify-center mx-auto mb-4">
          <span class="text-white text-2xl font-bold">1</span>
        </div>
        <h3 class="text-xl font-bold mb-2">Elige tu ruta</h3>
        <p class="text-gray-600">Busca rutas de transporte público entre cualquier punto de Arequipa.</p>
      </div>
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-[#E74C3C] rounded-full flex items-center justify-center mx-auto mb-4">
          <span class="text-white text-2xl font-bold">2</span>
        </div>
        <h3 class="text-xl font-bold mb-2">Explora destinos</h3>
        <p class="text-gray-600">Descubre lugares turísticos, culturales y gastronómicos de la ciudad.</p>
      </div>
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-[#E74C3C] rounded-full flex items-center justify-center mx-auto mb-4">
          <span class="text-white text-2xl font-bold">3</span>
        </div>
        <h3 class="text-xl font-bold mb-2">Registra tu visita</h3>
        <p class="text-gray-600">Lleva un registro personal de los destinos que has visitado.</p>
      </div>
    </div>
  </div>
</section>
@endsection
