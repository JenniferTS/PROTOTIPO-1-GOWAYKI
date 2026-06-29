<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña — GoWayki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F5F5F7] flex items-center justify-center px-4 overflow-hidden">

    @include('partials.auth-background')

    <div class="relative z-10 w-full max-w-md">
        @include('partials.auth-brand')

        <div class="bg-white/70 backdrop-blur-2xl border border-white/70 rounded-[2rem] shadow-2xl p-8">
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

            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">¿Olvidaste tu contraseña?</h2>
                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                    Ingresa tu correo registrado y te enviaremos un enlace para crear una nueva contraseña.
                </p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

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
                        autofocus
                        placeholder="fabricio.rojas@tecsup.edu.pe"
                        class="w-full rounded-2xl border border-gray-200 bg-white/80 px-4 py-3.5 text-gray-800 shadow-sm outline-none transition focus:border-[#F83A34] focus:ring-4 focus:ring-[#FFE7E5]"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-[#F83A34] py-3.5 font-bold text-white shadow-lg shadow-red-200/80 transition hover:bg-[#D82027] hover:-translate-y-0.5 active:translate-y-0"
                >
                    Enviar enlace de recuperación
                </button>
            </form>

            <div class="text-center mt-7 text-sm text-gray-600">
                ¿Recordaste tu contraseña?
                <a href="{{ route('login') }}" class="font-bold text-[#F83A34] hover:underline">
                    Volver a ingresar
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            GoWayki · Recuperación segura de cuenta
        </p>
    </div>
</body>
</html>




