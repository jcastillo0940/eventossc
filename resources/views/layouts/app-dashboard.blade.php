<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .dashboard-card { background: white; border: 1px solid #e2e8f0; border-radius: 1.5rem; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 1rem; color: #475569; font-weight: 600; transition: all 0.2s; }
        .sidebar-link.active { background: #0ea5e9; color: white; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2); }
        [x-cloak] { display: none !important; }

        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.9); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1);   } to { opacity:0; transform: translateX(110%) scale(.9); } }
        .toast-enter { animation: toast-in  0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 antialiased">

    {{-- GLOBAL TOAST --}}
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
    @if(session('warning'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.warning(@json(session('warning'))), 100); })</script>
    @endif
    @if(session('info'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.info(@json(session('info'))), 100); })</script>
    @endif

    <div class="flex min-h-screen">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out flex-none"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
            <div class="flex flex-col h-full">
                <div class="p-8 pb-10 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-2xl font-black italic tracking-tighter text-slate-900">
                        PRO<span class="text-sky-500">EVENTS</span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="flex-grow px-4 space-y-1 overflow-y-auto">
                    @role('Digitador')
                    <div class="px-4 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Digitación</div>
                    <a href="{{ route('digitizer.index') }}" class="sidebar-link {{ request()->routeIs('digitizer.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        <span>Escanear / Digitar</span>
                    </a>
                    @endrole

                    @role('Juez')
                    <div class="px-4 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Jurado</div>
                    <a href="{{ route('judge.dashboard') }}" class="sidebar-link {{ request()->routeIs('judge.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Mis Calificaciones</span>
                    </a>
                    @endrole

                    @role('Participante')
                    <div class="px-4 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Competidor</div>
                    <a href="{{ route('participant.dashboard') }}" class="sidebar-link {{ request()->routeIs('participant.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Mi Portal</span>
                    </a>
                    @endrole

                    <div class="px-4 pt-6 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Cuenta</div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();" class="sidebar-link text-red-400 hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Cerrar Sesión</span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col min-h-screen w-0">
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30 flex-none">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden lg:block text-slate-400 text-xs font-black uppercase tracking-widest italic">
                     @yield('header_title', 'Dashboard')
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-black text-slate-900 uppercase italic">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold text-sky-500 uppercase tracking-widest">@yield('role_name', 'Usuario')</div>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 border-2 border-slate-200 flex items-center justify-center text-slate-900 font-black text-xs uppercase italic">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
            </header>

            <main class="p-8 lg:p-12 flex-grow overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

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

    @stack('scripts')
</body>
</html>
