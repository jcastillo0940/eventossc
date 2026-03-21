<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODO TARIMA - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #020617;
            color: #ffffff;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .bg-gradient-tarima {
            background: radial-gradient(circle at center, #0f172a 0%, #020617 100%);
        }
        .flip-card-inner {
            transition: transform 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .flip-card-reveal {
            transform: rotateY(180deg);
        }
        .reveal-fade .flip-card-front { opacity: 0; transition: opacity 1s; }
        .reveal-fade .flip-card-back { opacity: 1; transform: rotateY(0deg); transition: opacity 1s; }
        .reveal-scale .flip-card-front { transform: scale(0); transition: transform 0.8s; }
        .reveal-scale .flip-card-back { transform: scale(1) rotateY(0deg); transition: transform 0.8s; }
        .flip-card-front, .flip-card-back {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }
        .flip-card-back {
            transform: rotateY(180deg);
        }
        @keyframes shine {
            0% { background-position: 200% center; }
            100% { background-position: -200% center; }
        }
        .shine-effect {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 200% 100%;
            animation: shine 3s linear infinite;
        }
    </style>
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: '{{ config('broadcasting.connections.reverb.options.host') }}',
            wsPort: 443,
            wssPort: 443,
            forceTLS: true,
            enabledTransports: ['ws', 'wss'],
        });
    </script>
</head>
<body class="bg-gradient-tarima min-h-screen flex items-center justify-center p-0 m-0 cursor-none">
    {{ $slot }}
    @livewireScripts
    @stack('scripts')
</body>
</html>
