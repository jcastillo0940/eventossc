<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eventos Pro')</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fingerprint JS for Voting -->
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #0f172a; 
            color: #f8fafc;
            scroll-behavior: smooth;
        }
        .glass { 
            background: rgba(30, 41, 59, 0.7); 
            backdrop-filter: blur(12px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
        }
        .text-gradient {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.4);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.9); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1);   } to { opacity:0; transform: translateX(110%) scale(.9); } }
        .toast-enter { animation: toast-in  0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="antialiased overflow-x-hidden">

    {{-- GLOBAL TOAST SYSTEM --}}
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
                    'bg-white/95 border-emerald-100': toast.type === 'success',
                    'bg-white/95 border-red-100':     toast.type === 'error',
                    'bg-white/95 border-amber-100':   toast.type === 'warning',
                    'bg-white/95 border-sky-100':     toast.type === 'info'
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
                    <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <svg x-show="toast.type === 'info'" class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-black italic tracking-tighter text-white">
                    PRO<span class="text-sky-400">EVENTS</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}#upcoming" class="text-sm font-semibold hover:text-sky-400 transition-colors">Eventos</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold bg-slate-800 px-4 py-2 rounded-lg hover:bg-slate-700 transition">Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-sky-400 transition-colors">Acceso</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-16">
        {{ $slot ?? '' }}
        @yield('content')
    </div>

    <footer class="bg-slate-900 border-t border-slate-800 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm italic mb-4 font-bold tracking-widest">PROVENTS SYSTEM &copy; 2026</p>
            <p class="text-slate-600 text-xs">Diseñado para la excelencia en competencias profesionales.</p>
        </div>
    </footer>

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
