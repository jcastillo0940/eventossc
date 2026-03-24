@extends('layouts.public')

@section('title', 'Eventos  | Super Carnes')

@section('content')

    <section class="relative min-h-[85vh] flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, #1A6FBF 0%, #0d4a8a 60%, #0a3a6e 100%);">
        
        {{-- Blob decorativo amarillo --}}
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full opacity-20 blur-[80px]" style="background: #F5C400;"></div>
        <div class="absolute -bottom-32 -left-32 w-[400px] h-[400px] rounded-full opacity-15 blur-[80px]" style="background: #F5C400;"></div>

        {{-- Patrón de puntos sutil --}}
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 32px 32px;"></div>

        {{-- Logo Super Carnes watermark --}}
        <div class="absolute top-8 left-8 z-10">
            <img src="https://eventos.supercarnes.com/storage/14/logo-super-carnes.png" 
                 alt="Super Carnes" class="h-32 object-contain drop-shadow-lg opacity-90">
        </div>

        <div class="relative z-10 text-center px-6 max-w-5xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-black uppercase tracking-[0.3em] mb-8 border border-white/20"
                 style="background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); color: #F5C400;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background: #F5C400;"></span>
                Eventos en Vivo
            </div>

            <h1 class="text-5xl md:text-7xl font-black uppercase leading-tight text-white mb-6 drop-shadow-lg">
                Eventos <br>
                <span style="color: #F5C400;">Super Carnes</span>
            </h1>
            <p class="text-lg md:text-xl text-white/70 font-medium mb-12 max-w-2xl mx-auto leading-relaxed">
                Descubre nuestros eventos y competencias familiares. Vota por tus favoritos en tiempo real y vive la emoción junto a toda la familia.
            </p>

            <div class="flex justify-center">
                <a href="#upcoming" 
                   class="px-10 py-4 rounded-2xl font-black text-base uppercase tracking-wider transition-all transform hover:scale-105 active:scale-95 shadow-2xl"
                   style="background: #F5C400; color: #1A6FBF;">
                    Explorar Eventos
                </a>
            </div>
        </div>

        {{-- Wave decorativa --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L1440 80L1440 30C1200 80 960 0 720 30C480 60 240 0 0 30L0 80Z" fill="#F0EDE8"/>
            </svg>
        </div>
    </section>

    <main id="upcoming" style="background: #F0EDE8;" class="px-4 py-24 space-y-24">

        <section class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-14 gap-4">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-2 block" style="color: #1A6FBF;">
                        ● PRÓXIMAMENTE
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                        ¡No te pierdas<br>
                        <span style="color: #1A6FBF;">los próximos eventos!</span>
                    </h2>
                </div>
                <div class="text-slate-400 text-sm font-medium">
                    Actualizado: {{ now()->format('d/m/Y') }}
                </div>
            </div>

            @if($upcomingEvents->isEmpty())
                <div class="rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-300 bg-white/60"
                     style="backdrop-filter: blur(12px);">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"
                         style="background: #F5C400;">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-xl font-semibold">No hay eventos próximos en este momento.</p>
                    <p class="text-slate-400 text-sm mt-2">¡Vuelve pronto!</p>
                </div>

            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($upcomingEvents as $event)
                        <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-white">
                            
                            {{-- Imagen --}}
                            <div class="relative h-56 overflow-hidden">
                                @if($event->banner_path)
                                    @php
                                        $bannerUrl = $event->banner_path;
                                        if (!str_starts_with($bannerUrl, 'http')) {
                                            $bannerUrl = secure_asset('storage/' . ltrim($bannerUrl, '/'));
                                        } else {
                                            $bannerUrl = str_replace('http://', 'https://', $bannerUrl);
                                        }
                                    @endphp
                                    <img src="{{ $bannerUrl }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                         alt="{{ $event->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"
                                         style="background: linear-gradient(135deg, #1A6FBF, #0d4a8a);">
                                        <span class="text-white/30 font-black text-2xl uppercase">{{ $event->name }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                                {{-- Badge PRÓXIMO --}}
                                <div class="absolute top-4 left-4">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-white shadow-lg"
                                          style="background: #1A6FBF; backdrop-filter: blur(8px);">
                                        PRÓXIMO
                                    </span>
                                </div>

                                {{-- Logo del evento --}}
                                @if($event->logo_path)
                                    @php
                                        $logoUrl = $event->logo_path;
                                        if (!str_starts_with($logoUrl, 'http')) {
                                            $logoUrl = secure_asset('storage/' . ltrim($logoUrl, '/'));
                                        } else {
                                            $logoUrl = str_replace('http://', 'https://', $logoUrl);
                                        }
                                    @endphp
                                    <div class="absolute bottom-4 right-4 w-12 h-12 rounded-2xl bg-white shadow-lg p-1.5">
                                        <img src="{{ $logoUrl }}" class="w-full h-full object-contain">
                                    </div>
                                @endif
                            </div>

                            {{-- Contenido --}}
                            <div class="p-8 space-y-5">
                                {{-- Fecha --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" style="background: #F5C400;">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-500">
                                        {{ $event->getFullFormattedDate() }}
                                    </span>
                                </div>

                                {{-- Nombre --}}
                                <h3 class="text-2xl font-black text-slate-800 leading-tight group-hover:transition-colors" 
                                    style="transition: color 0.3s;"
                                    onmouseover="this.style.color='#1A6FBF'" 
                                    onmouseout="this.style.color=''">
                                    {{ $event->name }}
                                </h3>

                                {{-- Separador amarillo --}}
                                <div class="h-1 w-10 rounded-full group-hover:w-20 transition-all duration-500"
                                     style="background: #F5C400;"></div>

                                {{-- Descripción --}}
                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-3">
                                    {{ $event->description ?: 'Próximamente más detalles sobre este emocionante evento.' }}
                                </p>

                                {{-- CTA --}}
                                <a href="{{ route('events.landing', $event->slug) }}"
                                   class="inline-flex items-center gap-3 px-7 py-3.5 rounded-2xl font-black text-sm uppercase tracking-wider text-white transition-all transform hover:scale-105 active:scale-95 shadow-lg w-full justify-center"
                                   style="background: #1A6FBF;">
                                    Ver Evento
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        @if(!$pastEvents->isEmpty())
        <section class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end gap-4 mb-12">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-2 block text-slate-400">
                        ● HISTORIAL DE COMPETENCIAS
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-700 tracking-tight">
                        Eventos <span class="text-slate-400">Pasados</span>
                    </h2>
                </div>
                <div class="hidden md:block h-px flex-grow mx-8 bg-slate-200"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($pastEvents as $event)
                    <a href="{{ route('events.landing', $event->slug) }}"
                       class="group relative h-64 rounded-[2rem] overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                        @if($event->banner_path)
                            @php
                                $pastBannerUrl = $event->banner_path;
                                if (!str_starts_with($pastBannerUrl, 'http')) {
                                    $pastBannerUrl = secure_asset('storage/' . ltrim($pastBannerUrl, '/'));
                                } else {
                                    $pastBannerUrl = str_replace('http://', 'https://', $pastBannerUrl);
                                }
                            @endphp
                            <img src="{{ $pastBannerUrl }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0">
                        @else
                            <div class="w-full h-full bg-slate-300 flex items-center justify-center">
                                <span class="text-slate-500 font-black uppercase text-sm">{{ $event->name }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/30 to-transparent"></div>

                        {{-- Badge finalizado --}}
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-slate-300 border border-white/20"
                                  style="background: rgba(0,0,0,0.4); backdrop-filter: blur(8px);">
                                FINALIZADO
                            </span>
                        </div>

                        <div class="absolute bottom-5 left-5 right-5">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1 block">
                                {{ $event->date ? $event->date->format('Y') : '' }}
                            </span>
                            <h3 class="text-lg font-black text-white line-clamp-1 group-hover:text-yellow-400 transition-colors">
                                {{ $event->name }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ===================== SECCIÓN MARCAS ALIADAS ===================== --}}
        @if($brands->count() > 0)
        <section class="max-w-7xl mx-auto">
            <div class="text-center mb-14 space-y-3">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 block">
                    ● Confían en Nosotros
                </span>
                <h2 class="text-3xl font-black text-slate-800 uppercase italic">
                    Marcas <span style="color: #1A6FBF;">Aliadas</span>
                </h2>
            </div>

            {{-- 
                Efecto: al hacer hover sobre un logo, ese crece (scale 1.35)
                y los demás se comprimen (scale 0.85) y bajan opacidad.
                Al salir del contenedor todo vuelve al estado normal.
            --}}
            <div class="brands-grid flex flex-wrap items-center justify-center gap-10 md:gap-16">
                @foreach($brands as $brand)
                    @php
                        $logoUrl = $brand->logo_path;
                        if (!str_starts_with($logoUrl, 'http')) {
                            $logoUrl = asset('storage/' . ltrim($logoUrl, '/'));
                        }
                    @endphp
                    <div class="brand-item flex items-center justify-center"
                         style="transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s ease;">
                        <img src="{{ $logoUrl }}"
                             class="h-10 md:h-14 w-auto object-contain"
                             style="opacity: 0.5; transition: opacity 0.35s ease;"
                             loading="lazy"
                             alt="{{ $brand->name }}">
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                <p class="text-slate-400 text-xs font-medium max-w-lg mx-auto leading-relaxed uppercase tracking-widest italic">
                    ¿Quieres que tu marca esté presente en nuestros próximos eventos? <br>
                    <a href="https://wa.link/c5wzcs" class="font-black hover:underline mt-2 inline-block" style="color: #1A6FBF;">Contáctanos aquí</a>
                </p>
            </div>
        </section>
        @endif

    </main>

@endsection

@push('styles')
<style>
    /* Estado activo: el logo hovered crece con un pequeño bounce */
    .brand-item.is-active {
        transform: scale(1.35);
        z-index: 10;
    }
    .brand-item.is-active img {
        opacity: 1 !important;
    }

    /* Estado inactivo: los demás logos se comprimen levemente */
    .brand-item.is-inactive {
        transform: scale(0.82);
        opacity: 0.4;
    }
    .brand-item.is-inactive img {
        opacity: 0.35 !important;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const grid = document.querySelector('.brands-grid');
    if (!grid) return;

    const items = Array.from(grid.querySelectorAll('.brand-item'));

    items.forEach(function (item) {
        item.addEventListener('mouseenter', function () {
            items.forEach(function (other) {
                if (other === item) {
                    other.classList.add('is-active');
                    other.classList.remove('is-inactive');
                } else {
                    other.classList.add('is-inactive');
                    other.classList.remove('is-active');
                }
            });
        });
    });

    grid.addEventListener('mouseleave', function () {
        items.forEach(function (item) {
            item.classList.remove('is-active', 'is-inactive');
        });
    });
})();
</script>
@endpush