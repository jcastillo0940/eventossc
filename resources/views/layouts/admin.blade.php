<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --sc-blue:   #1A6FBF;
            --sc-yellow: #F5C400;
            --sc-cream:  #F0EDE8;
            --sc-dark:   #0d3a6e;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #F0EDE8;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        .dashboard-card {
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            border-radius: 0.875rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link:hover {
            background: #F0EDE8;
            color: var(--sc-blue);
        }
        .sidebar-link.active {
            background: var(--sc-blue);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 111, 191, 0.25);
        }
        .sidebar-link.active svg { color: white; }

        .sidebar-section-label {
            padding: 0 0.75rem;
            margin-bottom: 0.375rem;
            margin-top: 1.25rem;
            font-size: 0.65rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--sc-blue);
            opacity: 0.6;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #F0EDE8; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--sc-blue); }

        [x-cloak] { display: none !important; }

        @keyframes toast-in  { from { opacity:0; transform: translateX(110%) scale(.9); } to { opacity:1; transform: translateX(0) scale(1); } }
        @keyframes toast-out { from { opacity:1; transform: translateX(0) scale(1); } to { opacity:0; transform: translateX(110%) scale(.9); } }
        .toast-enter { animation: toast-in  0.35s cubic-bezier(.22,1,.36,1) forwards; }
        .toast-leave  { animation: toast-out 0.3s ease-in forwards; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
        .toast-progress { animation: shrink linear forwards; }
    </style>
    @stack('styles')
</head>
<body class="antialiased">

    {{-- ===================== TOAST ===================== --}}
    <div
        x-data="toastManager()"
        x-init="init()"
        class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-80 pointer-events-none"
        aria-live="polite"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                class="pointer-events-auto flex items-start gap-4 px-5 py-4 rounded-2xl shadow-2xl border"
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
    @if(session('warning'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.warning(@json(session('warning'))), 100); })</script>
    @endif
    @if(session('info'))
        <script>document.addEventListener('alpine:init', () => { setTimeout(() => window.$toast?.info(@json(session('info'))), 100); })</script>
    @endif

    <div class="flex min-h-screen">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false" x-cloak></div>

        {{-- ===================== SIDEBAR ===================== --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-100 transform lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out flex-none"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
            <div class="flex flex-col h-full">

                {{-- Logo --}}
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <img src="https://eventos.supercarnes.com/storage/14/logo-super-carnes.png"
                             alt="Super Carnes" class="h-10 object-contain">
                        <div>
                            <div class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Portal</div>
                            <div class="text-sm font-black leading-tight" style="color: var(--sc-blue);">Administración</div>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Nav --}}
                <nav class="flex-grow px-4 py-4 overflow-y-auto space-y-0.5">

                    <div class="sidebar-section-label">Principal</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>

                    <div class="sidebar-section-label">Gestión</div>
                    <a href="{{ route('admin.events.index') }}" class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Eventos</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Staff</span>
                    </a>
                    <a href="{{ route('admin.judges.index') }}" class="sidebar-link {{ request()->routeIs('admin.judges.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Cuerpo de Jueces</span>
                    </a>
                    <a href="{{ route('admin.participants.index') }}" class="sidebar-link {{ request()->routeIs('admin.participants.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Participantes</span>
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Patrocinadores</span>
                    </a>

                    <div class="sidebar-section-label">Ceremonia</div>
                    <a href="{{ route('admin.tarima.index') }}" class="sidebar-link {{ request()->routeIs('admin.tarima.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>Modo Tarima</span>
                    </a>

                    <div class="sidebar-section-label">Auditoría</div>
                    <a href="{{ route('admin.audit.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Auditoría de Puntos</span>
                    </a>

                </nav>

                {{-- Footer sidebar --}}
                <div class="p-4 border-t border-slate-100 space-y-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-500 font-bold hover:bg-red-100 transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Cerrar Sesión
                        </button>
                    </form>
                    <p class="text-[10px] text-slate-300 font-medium text-center">
                        Super Carnes Eventos &copy; {{ date('Y') }}
                    </p>
                </div>

            </div>
        </aside>

        {{-- ===================== CONTENT ===================== --}}
        <div class="flex-1 flex flex-col min-h-screen w-0">

            {{-- Header --}}
            <header class="bg-white/80 border-b border-slate-100 flex items-center justify-between px-6 lg:px-8 sticky top-0 z-30 flex-none"
                    style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); height: 4.5rem;">

                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:bg-slate-50 rounded-xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="hidden lg:flex items-center gap-2">
                    <span class="text-sm text-slate-300 font-medium">Super Carnes</span>
                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-sm font-black uppercase tracking-widest" style="color: var(--sc-blue);">
                        @yield('header_title', 'Administración')
                    </span>
                </div>

                {{-- User --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-black text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest" style="color: var(--sc-yellow);">
                            SuperAdmin
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white font-black text-sm uppercase shadow-sm"
                         style="background: linear-gradient(135deg, var(--sc-blue), var(--sc-dark));">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                </div>
            </header>

            {{-- Main --}}
            <main class="p-6 lg:p-10 flex-grow overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    {{ $slot ?? '' }}
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

    @livewireScripts
    @stack('scripts')
</body>
</html>
