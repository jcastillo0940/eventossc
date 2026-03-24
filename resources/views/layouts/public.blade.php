<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SOLUCIÓN AL ERROR DE MIXED CONTENT (HTTP vs HTTPS) --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    
    <title>@yield('title', 'Eventos Super Carnes')</title>
    
    {{-- Tipografía y Estilos --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>

    <style>
        :root {
            --sc-blue:   #1A6FBF;
            --sc-yellow: #F5C400;
            --sc-cream:  #F0EDE8;
            --sc-dark:   #0d3a6e;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--sc-cream);
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            scroll-behavior: smooth;
        }

        /* Scrollbar Personalizada */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--sc-cream); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--sc-blue); }

        /* Animaciones Toast */
        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.95); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1); } to { opacity:0; transform: translateX(110%) scale(.95); } }
        .toast-enter { animation: toast-in 0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>

    @stack('styles')
    @livewireStyles
</head>
<body class="antialiased overflow-x-hidden">

    {{-- SISTEMA DE NOTIFICACIONES (TOAST) --}}
    <div x-data="toastManager()" x-init="init()" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-80 pointer-events-none" aria-live="polite">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="pointer-events-auto flex items-start gap-4 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-sm bg-white"
                :class="{ 'border-emerald-100': toast.type === 'success', 'border-red-100': toast.type === 'error', 'border-amber-100': toast.type === 'warning', 'border-sky-100': toast.type === 'info' }"
                :id="'toast-' + toast.id">
                
                <div class="flex-none w-9 h-9 rounded-xl flex items-center justify-center mt-0.5"
                     :class="{ 'bg-emerald-100': toast.type === 'success', 'bg-red-100': toast.type === 'error', 'bg-amber-100': toast.type === 'warning', 'bg-sky-100': toast.type === 'info' }">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <svg x-show="toast.type === 'info'" class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black uppercase tracking-tight" :class="{ 'text-emerald-800': toast.type === 'success', 'text-red-700': toast.type === 'error', 'text-amber-800': toast.type === 'warning', 'text-sky-800': toast.type === 'info' }" x-text="toast.title"></p>
                    <p x-show="toast.message" x-text="toast.message" class="text-xs text-slate-500 mt-0.5 font-medium leading-relaxed"></p>
                    <div class="mt-3 h-0.5 rounded-full overflow-hidden bg-slate-100">
                        <div class="h-full rounded-full toast-progress" :style="'animation-duration:' + toast.duration + 'ms'" :class="{ 'bg-emerald-400': toast.type === 'success', 'bg-red-400': toast.type === 'error', 'bg-amber-400': toast.type === 'warning', 'bg-sky-400': toast.type === 'info' }"></div>
                    </div>
                </div>

                <button @click="remove(toast.id)" class="flex-none text-slate-300 hover:text-slate-500 transition mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    {{-- MANEJO DE SESIONES (MENSAJES) --}}
    @if(session('success'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.success(@json(session('success'))), 100); })</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.error(@json(session('error'))), 100); })</script>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- FOOTER REDISEÑADO --}}
    <footer style="background: var(--sc-blue);" class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 py-14">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                
                {{-- Logo Principal (Color Original) --}}
<div class="flex-none">
    <img src="https://eventos.supercarnes.com/storage/14/logo-super-carnes.png"
         alt="Super Carnes"
         class="h-20 w-auto object-contain">
</div>

                {{-- Navegación Limpia --}}
                <nav class="flex items-center gap-12">
                    <a href="{{ route('home') }}" class="text-white/80 hover:text-white text-base font-semibold transition-all hover:translate-y-[-2px]">
                        Inicio
                    </a>
                    <a href="{{ route('home') }}#upcoming" class="text-white/80 hover:text-white text-base font-semibold transition-all hover:translate-y-[-2px]">
                        Eventos
                    </a>
                </nav>

                {{-- Info de Copyright --}}
                <div class="text-center md:text-right">
                    <p class="text-white text-sm font-bold tracking-tight">
                        Super Carnes S.A. &copy; {{ date('Y') }}
                    </p>
                    <p class="text-white/40 text-[11px] font-medium uppercase tracking-wider mt-1">
                        Derechos Reservados
                    </p>
                </div>

            </div>

            {{-- Separador --}}
            <div class="mt-12 mb-8 border-t border-white/10"></div>

            {{-- Créditos Finales --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                
                <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: var(--sc-yellow);"></span>
                    <span class="text-white/40 text-[10px] font-black uppercase tracking-[0.2em]">Panamá</span>
                </div>
                
                {{-- POWERED BY INNOVA360 --}}
                <div class="flex items-center gap-2 group">
                    <span class="text-white/20 text-[11px] font-medium tracking-tight group-hover:text-white/30 transition-colors">Powered by</span>
                    <span class="text-white/50 text-[12px] font-black tracking-tighter group-hover:text-white/80 transition-colors">Innova360</span>
                </div>

            </div>
        </div>
    </footer>

    {{-- SCRIPTS DE FUNCIONAMIENTO --}}
    <script>
    function toastManager() {
        return {
            toasts: [],
            _id: 0,
            init() {
                window.$toast = {
                    success: (title, message = '', duration = 4000) => this.add('success', title, message, duration),
                    error:   (title, message = '', duration = 6000) => this.add('error',   title, message, duration),
                    warning: (title, message = '', duration = 5000) => this.add('warning', title, message, duration),
                    info:    (title, message = '', duration = 4000) => this.add('info',    title, message, duration),
                };
            },
            add(type, title, message, duration) {
                const id = ++this._id;
                this.toasts.push({ id, type, title, message, duration });
                this.$nextTick(() => {
                    const el = document.getElementById('toast-' + id);
                    if (el) el.classList.add('toast-enter');
                });
                setTimeout(() => this.remove(id), duration);
            },
            remove(id) {
                const el = document.getElementById('toast-' + id);
                if (el) {
                    el.classList.add('toast-leave');
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 320);
                } else {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }
            }
        }
    }
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>