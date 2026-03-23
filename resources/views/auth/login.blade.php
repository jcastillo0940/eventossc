<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EventOS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-vh-100 h-screen">
    <div class="w-full max-w-md p-8 glass rounded-2xl shadow-2xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white mb-2">Bienvenido</h1>
            <p class="text-slate-400">Ingresa a tu panel de gestión</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Correo Electrónico</label>
                <input type="email" name="email" required 
                    class="w-full px-4 py-3 rounded-lg bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"
                    placeholder="admin@evento.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Contraseña</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-lg bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all"
                    placeholder="••••••••">
            </div>

            <button type="submit" 
                class="w-full py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-lg shadow-lg hover:from-amber-600 hover:to-orange-700 transition-all transform hover:scale-[1.02]">
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="/" class="text-slate-400 hover:text-white transition-colors text-sm">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
