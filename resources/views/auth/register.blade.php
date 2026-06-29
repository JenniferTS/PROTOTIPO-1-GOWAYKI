<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta — GoWayki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F5F5F7] flex items-center justify-center px-4 overflow-hidden">

    @include('partials.auth-background')

    <div class="relative z-10 w-full max-w-md">
        @include('partials.auth-brand')

        <div class="bg-white/70 backdrop-blur-2xl border border-white/70 rounded-[2rem] shadow-2xl p-8">
            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-[#FFE7E5]/80 text-[#D82027] px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre completo
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="Juan Pérez"
                        class="w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3.5 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="tu@correo.com"
                        class="w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3.5 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            placeholder="Mínimo 8 caracteres"
                            class="w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3.5 pr-12 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#F83A34] transition"
                            aria-label="Mostrar contraseña"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirmar contraseña
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            minlength="8"
                            placeholder="Repite la contraseña"
                            class="w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3.5 pr-12 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#F83A34] transition"
                            aria-label="Mostrar contraseña"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-[#F83A34] py-3.5 font-bold text-white shadow-lg shadow-red-200/80 transition hover:bg-[#D82027] hover:-translate-y-0.5 active:translate-y-0"
                >
                    Crear Cuenta
                </button>
            </form>

            <div class="text-center mt-7 text-sm text-gray-600 space-y-2">
                <p>
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="font-bold text-[#F83A34] hover:underline">
                        Ingresar
                    </a>
                </p>

                <p>
                    ¿Olvidaste tu contraseña?
                    <a href="{{ route('password.request') }}" class="font-bold text-[#F83A34] hover:underline">
                        Recuperarla
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            GoWayki · Tu ruta más clara, rápida y segura
        </p>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>





