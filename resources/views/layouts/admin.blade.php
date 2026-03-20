<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .dashboard-card { background: white; border: 1px solid #e2e8f0; border-radius: 1.5rem; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 1rem; color: #475569; font-weight: 600; transition: all 0.2s; }
        .sidebar-link.active { background: #0ea5e9; color: white; box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2); }
        [x-cloak] { display: none !important; }

        /* Toast animations */
        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.9); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1);   } to { opacity:0; transform: translateX(110%) scale(.9); } }
        .toast-enter { animation: toast-in  0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }

        /* Progress bar shrink */
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>
    @stack('styles')
    @livewireStyles
</head>
<body class="bg-slate-50 antialiased">

    {{-- ═══════════════════════════════════════════════
         GLOBAL TOAST SYSTEM (Alpine.js)
         Usage from Blade: session('success') / session('error') / session('warning') / session('info')
         Usage from JS: window.$toast.success('Texto') / .error() / .warning() / .info()
    ═══════════════════════════════════════════════ --}}
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
                {{-- Icon --}}
                <div class="flex-none w-9 h-9 rounded-xl flex items-center justify-center mt-0.5"
                     :class="{
                        'bg-emerald-100': toast.type === 'success',
                        'bg-red-100':     toast.type === 'error',
                        'bg-amber-100':   toast.type === 'warning',
                        'bg-sky-100':     toast.type === 'info'
                     }">
                    {{-- Success --}}
                    <svg x-show="toast.type === 'success'" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{-- Error --}}
                    <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{-- Warning --}}
                    <svg x-show="toast.type === 'warning'" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    {{-- Info --}}
                    <svg x-show="toast.type === 'info'" class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                {{-- Content --}}
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

                    {{-- Progress bar --}}
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

                {{-- Close button --}}
                <button @click="remove(toast.id)" class="flex-none text-slate-300 hover:text-slate-500 transition mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    {{-- Fire Laravel flash messages as toasts on page load --}}
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
        <!-- Sidebar Mobile Overlay -->
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
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black italic tracking-tighter text-slate-900">
                        PRO<span class="text-sky-500">ADMIN</span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <nav class="flex-grow px-4 space-y-1 overflow-y-auto">
                    <div class="px-4 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Principal</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="px-4 pt-6 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Gestión</div>
                    <a href="{{ route('admin.events.index') }}" class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Eventos</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Staff (Admin/Dig)</span>
                    </a>
                    <a href="{{ route('admin.judges.index') }}" class="sidebar-link {{ request()->routeIs('admin.judges.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Cuerpo de Jueces</span>
                    </a>
                    <a href="{{ route('admin.participants.index') }}" class="sidebar-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Participantes</span>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Patrocinadores</span>
                    </a>

                    <div class="px-4 pt-6 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Ceremonia</div>
                    <a href="{{ route('admin.tarima.index') }}" class="sidebar-link {{ request()->routeIs('admin.tarima.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>Modo Tarima</span>
                    </a>

                    <div class="px-4 pt-6 mb-4 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Auditoría</div>
                    <a href="{{ route('admin.audit.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Auditoría de Puntos</span>
                    </a>
                </nav>

                <div class="p-6 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col min-h-screen w-0">
            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30 flex-none">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="hidden lg:block text-slate-400 text-xs font-black uppercase tracking-widest italic">
                     @yield('header_title', 'Administración')
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-black text-slate-900 uppercase italic">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold text-sky-500 uppercase tracking-widest">SuperAdmin</div>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-slate-900 border-2 border-slate-800 shadow-xl shadow-slate-900/10 flex items-center justify-center text-white font-black text-xs uppercase italic">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
            </header>

            <main class="p-8 lg:p-12 flex-grow overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- ═══════ TOAST MANAGER SCRIPT ═══════ --}}
    <script>
    function toastManager() {
        return {
            toasts: [],
            _id: 0,
            init() {
                // Expose globally so any JS can call window.$toast.success(...)
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
                // Animate in
                this.$nextTick(() => {
                    const el = document.getElementById('toast-' + id);
                    if (el) { el.classList.add('toast-enter'); }
                });
                // Auto remove
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
