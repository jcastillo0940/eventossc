<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Credencial - {{ $participant->event->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .gradient-text { background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full glass rounded-3xl p-8 shadow-2xl space-y-8 text-center">
        <div>
            <h1 class="text-3xl font-bold gradient-text">Mi Credencial</h1>
            <p class="text-slate-400 mt-2">{{ $participant->event->name }}</p>
        </div>

        <div class="relative inline-block">
            <div class="absolute -inset-1 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-2xl blur opacity-25"></div>
            <div class="relative bg-white p-4 rounded-2xl">
                <img src="{{ $participant->photo_path }}" alt="QR Code" class="w-64 h-64">
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl font-semibold">{{ $participant->name }}</h2>
            <p class="text-sky-400 font-medium tracking-wide uppercase text-sm">{{ $participant->category }}</p>
        </div>

        <div class="pt-6 border-t border-slate-700/50">
            <p class="text-xs text-slate-500 uppercase tracking-widest mb-4">Patrocinadores</p>
            <div class="flex flex-wrap justify-center gap-6 opacity-60 grayscale hover:grayscale-0 transition-all">
                @foreach($participant->event->brands as $brand)
                    <img src="{{ $brand->logo_path }}" alt="{{ $brand->name }}" class="h-8 object-contain">
                @endforeach
            </div>
        </div>

        <div class="pt-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $participant->status === 'activo' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }}">
                <span class="w-2 h-2 rounded-full {{ $participant->status === 'activo' ? 'bg-emerald-400' : 'bg-red-400' }} mr-2"></span>
                Estado: {{ ucfirst($participant->status) }}
            </span>
        </div>
    </div>
</body>
</html>
