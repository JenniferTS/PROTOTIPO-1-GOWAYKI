<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ingresar — GoWayki</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-4xl font-bold text-[#E74C3C]">GoWayki</a>
            <p class="text-gray-600 mt-2">Ingresa a tu cuenta</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent @error('email') border-red-500 @enderror"
                        placeholder="tu@correo.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="********">
                        <button type="button" data-toggle-password="#password"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-[#E74C3C] focus:ring-[#E74C3C] border-gray-300 rounded">
                    <label for="remember" class="ml-2 text-gray-600">Recordarme</label>
                </div>

                <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-bold py-3 rounded-lg transition">
                    Ingresar
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-[#E74C3C] hover:underline font-semibold">Crear Cuenta</a>
            </p>
        </div>
    </div>
</body>
</html>
