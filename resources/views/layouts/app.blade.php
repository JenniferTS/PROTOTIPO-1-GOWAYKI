<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GoWayki — Transporte inteligente en Arequipa')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">

    <nav class="navbar-gowayki shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
    <img src="{{ asset('images/brand/gowayki-logo-white.png') }}" alt="GoWayki" class="h-11 w-auto object-contain">
</a>
                </a>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-white hover:text-gray-200 font-medium transition">Inicio</a>
                    <a href="{{ route('rutas.index') }}" class="text-white hover:text-gray-200 font-medium transition">Rutas</a>
                    <a href="{{ route('destinos.index') }}" class="text-white hover:text-gray-200 font-medium transition">Destinos</a>
                    <a href="{{ route('recorridos.planificar') }}" class="text-white hover:text-gray-200 font-medium transition">Planificar</a>

                    @auth
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-white hover:text-gray-200 font-medium">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('recorridos.miRuta') }}" class="block px-4 py-2 text-gray-700 hover:bg-[#FFF3F2] hover:text-[#F83A34] rounded-t-lg">Mi Ruta</a>
                                <a href="{{ route('perfil.progreso') }}" class="block px-4 py-2 text-gray-700 hover:bg-[#FFF3F2] hover:text-[#F83A34]">Mi Progreso</a>
                                @can('admin')
                                    <a href="{{ route('admin.rutas.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-[#FFF3F2] hover:text-[#F83A34]">Admin</a>
                                @endcan
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-[#FFF3F2] hover:text-[#F83A34] rounded-b-lg">Salir</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-gray-200 font-medium transition">Ingresar</a>
                        <a href="{{ route('register') }}" class="bg-white text-[#F83A34] hover:bg-gray-100 font-semibold px-4 py-2 rounded-lg transition">Crear Cuenta</a>
                    @endauth
                </div>

                <button id="mobile-menu-btn" class="md:hidden text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-red-700 px-4 pb-4 space-y-2">
            <a href="{{ route('home') }}" class="block text-white py-2 font-medium">Inicio</a>
            <a href="{{ route('rutas.index') }}" class="block text-white py-2 font-medium">Rutas</a>
            <a href="{{ route('destinos.index') }}" class="block text-white py-2 font-medium">Destinos</a>
            <a href="{{ route('recorridos.planificar') }}" class="block text-white py-2 font-medium">Planificar</a>
            @auth
                <a href="{{ route('recorridos.miRuta') }}" class="block text-white py-2 font-medium">Mi Ruta</a>
                <a href="{{ route('perfil.progreso') }}" class="block text-white py-2 font-medium">Mi Progreso</a>
                @can('admin')
                    <a href="{{ route('admin.rutas.index') }}" class="block text-white py-2 font-medium">Admin</a>
                @endcan
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block text-white py-2 font-medium">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-white py-2 font-medium">Ingresar</a>
                <a href="{{ route('register') }}" class="block text-white py-2 font-medium">Crear Cuenta</a>
            @endauth
        </div>
    </nav>

    @if (session('success'))
        <div id="flash-message" class="bg-green-500 text-white px-4 py-3 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="flash-message" class="bg-[#FFF3F2]0 text-white px-4 py-3 text-center font-medium">
            {{ session('error') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">GoWayki</h3>
                    <p class="text-gray-400">Transporte inteligente y exploración de destinos en Arequipa, Perú.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Enlaces</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('rutas.index') }}" class="hover:text-white transition">Rutas de Transporte</a></li>
                        <li><a href="{{ route('destinos.index') }}" class="hover:text-white transition">Destinos Turísticos</a></li>
                        <li><a href="{{ route('recorridos.planificar') }}" class="hover:text-white transition">Planificar Recorrido</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Arequipa, Perú</li>
                        <li>info@gowayki.com</li>
                        <li>(+51) 987 654 321</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-500">
                &copy; {{ date('Y') }} GoWayki. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    @stack('scripts')
    @include('partials.footer')
</body>
</html>




