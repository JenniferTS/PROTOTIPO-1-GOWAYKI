<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Cuenta — GoWayki</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-4xl font-bold text-[#E74C3C]">GoWayki</a>
            <p class="text-gray-600 mt-2">Crea tu cuenta</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="Juan Pérez">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                            placeholder="Mínimo 8 caracteres">
                        <button type="button" data-toggle-password="#password"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-[#E74C3C] focus:border-transparent"
                        placeholder="Repite la contraseña">
                </div>

                <button type="submit" class="w-full bg-[#E74C3C] hover:bg-[#C0392B] text-white font-bold py-3 rounded-lg transition">
                    Crear Cuenta
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[#E74C3C] hover:underline font-semibold">Ingresar</a>
            </p>
        </div>
    </div>
</body>
</html>
