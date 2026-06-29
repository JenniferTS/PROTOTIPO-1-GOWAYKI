<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar — GoWayki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F5F5F7] flex items-center justify-center px-4 overflow-hidden">

    @include('partials.auth-background')

    <div class="relative z-10 w-full max-w-md">
        @include('partials.auth-brand')

        <div class="bg-white/78 backdrop-blur-2xl border border-white/90 rounded-[2rem] shadow-2xl shadow-gray-900/10 p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">Bienvenido de nuevo</h1>
                <p class="text-gray-500 text-sm mt-1">Ingresa a tu cuenta y planifica tu ruta</p>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-2xl bg-green-100/80 text-green-800 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-[#FFE7E5]/80 text-[#D82027] px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com"
                        class="w-full rounded-2xl border border-gray-200 bg-white/90 px-4 py-3.5 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
    <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>

    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#F83A34] hover:underline">
        ¿Olvidaste tu contraseña?
    </a>
</div>

                    <div class="relative">
                        <input id="password" type="password" name="password" required placeholder="********"
                            class="w-full rounded-2xl border border-gray-200 bg-white/90 px-4 py-3.5 pr-12 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#F83A34] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#F83A34] focus:ring-[#F83A34]">
                    Recordarme
                </label>

                <button type="submit"
                    class="w-full rounded-2xl bg-[#F83A34] py-3.5 font-bold text-white shadow-lg shadow-red-200/80 transition hover:bg-[#D82027] hover:-translate-y-0.5">
                    Ingresar
                </button>
            </form>

            <div class="text-center mt-7 text-sm text-gray-600">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="font-bold text-[#F83A34] hover:underline">Crear Cuenta</a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            GoWayki · Rutas urbanas para moverte mejor por Arequipa
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




