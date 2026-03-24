<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eventos Super Carnes')</title>
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

        * { box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--sc-cream);
            color: #1e293b;
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
        }

        /* Glassmorphism al estilo Super Carnes (fondo claro) */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .glass-dark {
            background: rgba(13, 58, 110, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--sc-cream); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--sc-blue); }

        /* Toast animations */
        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.95); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1); } to { opacity:0; transform: translateX(110%) scale(.95); } }
        .toast-enter { animation: toast-in  0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>

    @stack('styles')
    @livewireStyles
</head>
<body class="antialiased overflow-x-hidden">

    {{-- ===================== TOAST SYSTEM ===================== --}}
    <div
        x-data="toastManager()"
        x-init="init()"
        class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-80 pointer-events-none"
        aria-live="polite"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                class="pointer-events-auto flex items-start gap-4 px-5 py-4 rounded-2xl shadow-2xl border backdrop-blur-sm"
                :class="{
                    'bg-white border-emerald-100': toast.type === 'success',
                    'bg-white border-red-100':     toast.type === 'error',
                    'bg-white border-amber-100':   toast.type === 'warning',
                    'bg-white border-sky-100':     toast.type === 'info'
                }"
                :id="'toast-' + toast.id"
            >
                <div class="flex-none w-9 h-9 rounded-xl flex items-center justify-center mt-0.5"
                     :class="{
                        'bg-emerald-100': toast.type === 'success',
                        'bg-red-100':     toast.type === 'error',
                        'bg-amber-100':   toast.type === 'warning',
                        'bg-sky-100':     toast.type === 'info'
                     }">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="toast.type === 'error'"   class="w-5 h-5 text-red-500"     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-amber-500"   fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <svg x-show="toast.type === 'info'"    class="w-5 h-5 text-sky-500"     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black uppercase tracking-tight"
                       :class="{
                           'text-emerald-800': toast.type === 'success',
                           'text-red-700':     toast.type === 'error',
                           'text-amber-800':   toast.type === 'warning',
                           'text-sky-800':     toast.type === 'info'
                       }"
                       x-text="toast.title"></p>
                    <p x-show="toast.message" x-text="toast.message" class="text-xs text-slate-500 mt-0.5 font-medium leading-relaxed"></p>
                    <div class="mt-3 h-0.5 rounded-full overflow-hidden bg-slate-100">
                        <div class="h-full rounded-full toast-progress"
                             :style="'animation-duration:' + toast.duration + 'ms'"
                             :class="{
                                 'bg-emerald-400': toast.type === 'success',
                                 'bg-red-400':     toast.type === 'error',
                                 'bg-amber-400':   toast.type === 'warning',
                                 'bg-sky-400':     toast.type === 'info'
                             }"></div>
                    </div>
                </div>

                <button @click="remove(toast.id)" class="flex-none text-slate-300 hover:text-slate-500 transition mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    @if(session('success'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.success(@json(session('success'))), 100); })</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.error(@json(session('error'))), 100); })</script>
    @endif

    {{-- ===================== CONTENIDO ===================== --}}
    <div>
        {{ $slot ?? '' }}
        @yield('content')
    </div>

    {{-- ===================== FOOTER MEJORADO ===================== --}}
    <footer style="background: var(--sc-blue);" class="mt-0">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row items-center justify-between gap-10">

                {{-- Logo y Propósito --}}
                <div class="flex flex-col items-center md:items-start gap-2">
                    <img src="https://eventos.supercarnes.com/storage/14/logo-super-carnes.png"
                         alt="Super Carnes"
                         class="h-10 object-contain brightness-0 invert">
                    <p class="text-white/40 text-[10px] font-bold tracking-[0.2em] uppercase">
                        Eventos
                    </p>
                </div>

                {{-- Enlaces Rápidos --}}
                <nav class="flex items-center gap-10">
                    <a href="{{ route('home') }}" class="text-white/70 hover:text-white text-sm font-medium transition-colors">
                        Inicio
                    </a>
                    <a href="{{ route('home') }}#upcoming" class="text-white/70 hover:text-white text-sm font-medium transition-colors">
                        Eventos
                    </a>
                </nav>

                {{-- Copyright --}}
                <div class="text-center md:text-right">
                    <p class="text-white/50 text-xs font-semibold">
                        Super Carnes S.A. &copy; {{ date('Y') }}
                    </p>
                    <p class="text-white/30 text-[11px] mt-1">
                        Todos los derechos reservados.
                    </p>
                </div>

            </div>

            {{-- Línea de Cierre y Crédito --}}
            <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--sc-yellow);"></span>
                    <span class="text-white/30 text-[10px] font-bold tracking-widest uppercase">
                        Panamá
                    </span>
                </div>
                
                <a href="#" class="group flex items-center gap-1.5 no-underline">
                    <span class="text-white/20 text-[11px] transition-colors group-hover:text-white/40">Powered by</span>
                    <span class="text-white/40 text-[11px] font-bold transition-colors group-hover:text-white/60">Innova360</span>
                </a>
            </div>
        </div>
    </footer>

    {{-- ===================== SCRIPTS ===================== --}}
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
