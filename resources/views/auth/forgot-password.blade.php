<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña - GoWayki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-red-600">GoWayki</h1>
            <h2 class="text-xl font-bold text-gray-900 mt-3">Recuperar contraseña</h2>
            <p class="text-gray-600 text-sm mt-2">
                Ingrese su correo registrado y se generará un enlace para restablecer su contraseña.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="fabricio.rojas@tecsup.edu.pe"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition">
                Enviar enlace de recuperación
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-red-600 font-semibold hover:underline">
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</body>
</html>
