@extends('layouts.public')

@section('title', 'Explora Próximos Eventos | ProEvents')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[80vh] flex items-center justify-center overflow-hidden bg-[#0f172a]">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-600/20 mix-blend-overlay"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500 rounded-full blur-[120px] opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500 rounded-full blur-[120px] opacity-20 animate-pulse delay-75"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-8xl font-black italic uppercase italic leading-tight text-white mb-6">
                VIVE LA <span class="text-sky-400">EMOCIÓN</span> DE LOS EVENTOS <span class="text-indigo-400">PRO</span>
            </h1>
            <p class="text-lg md:text-2xl text-slate-400 font-medium mb-12 max-w-2xl mx-auto leading-relaxed">
                Descubre las competencias más impactantes del momento. Vota por tus favoritos en tiempo real y sé parte de la experiencia.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="#upcoming" class="px-10 py-5 bg-white text-indigo-900 rounded-2xl font-bold text-lg hover:bg-sky-400 hover:text-white transition-all transform hover:scale-105 active:scale-95 shadow-xl shadow-sky-400/20">
                    Explorar Eventos
                </a>
                <a href="{{ route('login') }}" class="px-10 py-5 glass border border-slate-700 text-white rounded-2xl font-bold text-lg hover:backdrop-blur-xl transition-all transform hover:scale-105">
                    Soy Juez / Admin
                </a>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Grid -->
    <main id="upcoming" class="max-w-7xl mx-auto px-4 py-32 space-y-32">
        <section>
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-16 gap-6">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-sky-400 mb-2 block">PRÓXIMAS COMPETENCIAS</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white tracking-tight">Vibrando con los <span class="italic underline decoration-sky-400">Próximos</span> Eventos</h2>
                </div>
                <div class="hidden md:block h-[1px] flex-grow mx-8 bg-slate-800"></div>
                <div class="text-slate-500 font-medium">Actualizado: {{ now()->format('d/m/Y') }}</div>
            </div>

            @if($upcomingEvents->isEmpty())
                <div class="glass p-12 rounded-[3rem] text-center border-dashed border-2 border-slate-700">
                    <p class="text-slate-500 text-xl font-medium mb-8 italic italic">No hay eventos próximos en este momento. ¡Vuelve pronto!</p>
                    <div class="w-24 h-24 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($upcomingEvents as $event)
                        <div class="group glass rounded-[3rem] overflow-hidden hover:border-sky-400/50 transition-all duration-500 hover:-translate-y-4">
                            <!-- Card Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if($event->banner_path)
                                    <img src="{{ Storage::url($event->banner_path) }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                         alt="{{ $event->name }}">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                        <span class="text-slate-600 font-black text-2xl uppercase italic italic tracking-wider">{{ $event->name }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent"></div>
                                <div class="absolute top-6 right-6">
                                    <div class="glass px-4 py-2 rounded-full text-xs font-black uppercase text-sky-400 tracking-widest border border-sky-400/30">
                                        PRÓXIMO
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="p-10 space-y-6">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3 text-sky-400">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm font-bold uppercase tracking-widest">{{ $event->date->format('d M, Y') }} | {{ $event->date->format('H:i') }}</span>
                                    </div>
                                    <h3 class="text-3xl font-black text-white italic italic group-hover:text-sky-400 transition-colors">{{ $event->name }}</h3>
                                    <div class="h-1 w-12 bg-indigo-500 rounded-full group-hover:w-24 transition-all duration-500"></div>
                                </div>
                                <p class="text-slate-400 text-sm leading-relaxed line-clamp-3 font-medium">
                                    {{ $event->description ?: 'No hay descripción disponible para este evento.' }}
                                </p>
                                <a href="{{ route('events.landing', $event->slug) }}" 
                                   class="inline-flex items-center gap-4 bg-white text-indigo-900 px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-sky-400 hover:text-white transition-all transform group-hover:scale-105 active:scale-95 shadow-xl shadow-indigo-600/10">
                                    Ver Evento
                                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Past Events Section -->
        @if(!$pastEvents->isEmpty())
        <section class="opacity-80 grayscale-[50%] hover:grayscale-0 transition-all duration-700">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block font-bold">HISTORIAL DE COMPETENCIAS</span>
                    <h2 class="text-3xl font-bold text-white tracking-tight">Resultados de <span class="text-slate-500 italic decoration-slate-700">Eventos</span> Pasados</h2>
                </div>
                <div class="h-[1px] flex-grow mx-8 bg-slate-800"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pastEvents as $event)
                    <a href="{{ route('events.landing', $event->slug) }}" class="group relative h-72 rounded-[2rem] overflow-hidden border border-slate-800 hover:border-slate-500 transition-all">
                        @if($event->banner_path)
                            <img src="{{ Storage::url($event->banner_path) }}" class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-125">
                        @else
                            <div class="w-full h-full bg-slate-900 flex items-center justify-center italic text-slate-700 font-bold uppercase">{{ $event->name }}</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1 block">{{ $event->date->format('Y') }}</span>
                            <h3 class="text-xl font-bold text-white group-hover:text-sky-400 transition-colors line-clamp-1 italic italic">{{ $event->name }}</h3>
                        </div>
                        <div class="absolute top-6 left-6 glass px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-700">
                            FINALIZADO
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif
    </main>

    <!-- Branding Section / Partners (Mockup if needed or just generic) -->
    <section class="py-24 bg-slate-900/50 border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs font-black tracking-[0.4em] text-slate-600 uppercase italic mb-8">Nuestros Principales Patrocinadores</p>
            <div class="flex flex-wrap items-center justify-center gap-12 opacity-30 invert">
                <!-- Fallback placeholders if no brands exist globally, but usually home showcases general ones -->
                <span class="text-4xl font-black opacity-20 italic italic">LOGO SAMPLE 01</span>
                <span class="text-4xl font-black opacity-40 italic italic italic">LOGO SAMPLE 02</span>
                <span class="text-4xl font-black opacity-30 italic italic italic">LOGO SAMPLE 03</span>
                <span class="text-4xl font-black opacity-50 italic italic italic">LOGO SAMPLE 04</span>
            </div>
        </div>
    </section>
@endsection
