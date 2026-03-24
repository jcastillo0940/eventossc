@extends('layouts.public')

@section('title', $event->name . ' | Super Carnes Eventos')

@section('content')
@php 
    $allPhotos = $event->getMedia('gallery_photos')->concat($event->getMedia('gallery')); 
@endphp

@push('scripts')
<script>
    window.eventPhotos = @json($allPhotos->map(fn($m) => str_replace('http://', 'https://', $m->getUrl()))->values());
    
    function eventLanding(targetDate, photosArr = []) {
        return {
            target: new Date(targetDate),
            countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
            eventStarted: false,
            voting: false,
            fingerprint: null,
            allPhotos: photosArr,
            selectedPhoto: null,
            currentIndex: null,

            async init() {
                if(window.FingerprintJS) {
                    const fp = await FingerprintJS.load();
                    const result = await fp.get();
                    this.fingerprint = result.visitorId;
                }
                this.updateCountdown();
                setInterval(() => this.updateCountdown(), 1000);
            },

            updateCountdown() {
                const now = new Date();
                const diff = this.target - now;
                if (diff <= 0) { this.eventStarted = true; return; }
                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);
                this.countdown.days    = d.toString().padStart(2, '0');
                this.countdown.hours   = h.toString().padStart(2, '0');
                this.countdown.minutes = m.toString().padStart(2, '0');
                this.countdown.seconds = s.toString().padStart(2, '0');
            },

            openLightbox(index) {
                this.currentIndex = index;
                this.selectedPhoto = this.allPhotos[index];
                document.body.style.overflow = 'hidden';
            },

            nextPhoto() {
                if (this.currentIndex !== null) {
                    this.currentIndex = (this.currentIndex + 1) % this.allPhotos.length;
                    this.selectedPhoto = this.allPhotos[this.currentIndex];
                }
            },

            prevPhoto() {
                if (this.currentIndex !== null) {
                    this.currentIndex = (this.currentIndex - 1 + this.allPhotos.length) % this.allPhotos.length;
                    this.selectedPhoto = this.allPhotos[this.currentIndex];
                }
            },

            closeLightbox() {
                this.selectedPhoto = null;
                this.currentIndex = null;
                document.body.style.overflow = 'auto';
            },

            async castVote(participantId) {
                if (!this.fingerprint) {
                    window.$toast?.warning("Cargando...", "El sistema de seguridad se está iniciando.");
                    return;
                }
                if (!confirm("¿Deseas registrar tu voto? Solo se permite un voto por persona.")) return;
                this.voting = true;
                try {
                    const res = await fetch('{{ route('public.vote') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({
                            event_id: {{ $event->id }},
                            participant_id: participantId,
                            fingerprint: this.fingerprint
                        })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        window.$toast?.success("¡Voto registrado!", "¡Gracias por participar!");
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        window.$toast?.error("No se pudo votar", data.error || "Es posible que ya hayas votado.");
                    }
                } catch (e) {
                    window.$toast?.error("Error de conexión", "No pudimos conectar con el servidor.");
                } finally {
                    this.voting = false;
                }
            }
        }
    }
</script>
@endpush

<div x-data="eventLanding('{{ $event->date->toISOString() }}', window.eventPhotos)" 
     @keydown.escape.window="closeLightbox()"
     @keydown.right.window="nextPhoto()"
     @keydown.left.window="prevPhoto()">

    {{-- ===================== HERO ===================== --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden py-24">

        {{-- Banner --}}
        <div class="absolute inset-0 z-0">
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
                     class="w-full h-full object-cover"
                     style="animation: subtle-zoom 12s ease-in-out infinite alternate;"
                     alt="{{ $event->name }}">
            @else
                <div class="w-full h-full" style="background: linear-gradient(135deg, #1A6FBF 0%, #0d3a6e 100%);"></div>
            @endif
        </div>

        {{-- Overlay azul oscuro --}}
        <div class="absolute inset-0 z-10"
             style="background: linear-gradient(to top, rgba(13,58,110,0.97) 0%, rgba(13,58,110,0.6) 50%, rgba(13,58,110,0.2) 100%);"></div>

        {{-- Blob amarillo decorativo --}}
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-20 blur-[80px] z-10"
             style="background: #F5C400;"></div>

        {{-- Contenido --}}
        <div class="relative z-20 text-center px-4 pb-16 md:pb-24 max-w-5xl mx-auto space-y-4 w-full">

            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-white/20 mb-2"
                 style="background: rgba(245,196,0,0.15); backdrop-filter: blur(8px); color: #F5C400;">
                <span class="w-2 h-2 rounded-full animate-ping" style="background: #F5C400;"></span>
                Evento En Vivo
            </div>

            <h1 class="text-4xl sm:text-6xl md:text-8xl font-black uppercase leading-tight text-white tracking-tighter drop-shadow-2xl px-2">
                {{ $event->name }}
            </h1>

            <p class="text-base md:text-2xl font-bold uppercase tracking-[0.2em]" style="color: #F5C400;">
                {{ $event->date->format('d M, Y') }} · {{ $event->date->format('g:i a') }}
            </p>

            {{-- Botones Hero --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                @if($event->brands->count() > 0)
                    <a href="#sponsors" 
                       class="px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all transform hover:scale-105 active:scale-95 shadow-xl bg-white text-sky-900 w-full sm:w-auto">
                        Marcas Aliadas
                    </a>
                @endif
                
                @if($allPhotos->count() > 0)
                    <a href="#full-gallery" 
                       class="px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all transform hover:scale-105 active:scale-95 shadow-xl bg-amber-500 text-white w-full sm:w-auto border-b-4 border-amber-700">
                        Ver Galería
                    </a>
                @endif

                <a href="{{ route('home') }}" 
                   class="px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all border border-white/30 text-white hover:bg-white/10 w-full sm:w-auto">
                    Regresar a inicio
                </a>
            </div>

            {{-- Countdown --}}
            <template x-if="!eventStarted">
                <div class="grid grid-cols-4 gap-3 md:gap-6 max-w-sm mx-auto mt-6 p-5 rounded-[2rem] border border-white/10"
                     style="background: rgba(255,255,255,0.08); backdrop-filter: blur(16px);">
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.days">00</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/40">Días</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.hours">00</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/40">Horas</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.minutes">00</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/40">Mins</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black block" x-text="countdown.seconds" style="color: #F5C400;">00</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/40">Segs</span>
                    </div>
                </div>
            </template>
        </div>
    </section>

    {{-- ===================== CARRUSEL SPONSORS (debajo del hero, desaparece con el scroll) ===================== --}}
    @if($event->brands->count() > 0)
    <div id="sponsors" class="relative overflow-hidden py-4" style="background: #1A6FBF;">

        {{-- Borde amarillo top --}}
        <div class="absolute top-0 left-0 right-0 h-[3px]" style="background: #F5C400;"></div>

        {{-- Fade masks izquierda / derecha --}}
        <div class="absolute inset-y-0 left-0 w-20 z-10 pointer-events-none"
             style="background: linear-gradient(to right, #1A6FBF, transparent);"></div>
        <div class="absolute inset-y-0 right-0 w-20 z-10 pointer-events-none"
             style="background: linear-gradient(to left, #1A6FBF, transparent);"></div>

        <div class="overflow-hidden">
            {{-- Solo 2 copias: suficiente para el loop continuo sin desperdiciar DOM --}}
            <div class="ticker-track flex items-center" style="width: max-content; white-space: nowrap;">
                @php $tickerBrands = $event->brands; @endphp

                @foreach([1,2] as $_)
                    @foreach($tickerBrands as $brand)
                        <div class="shrink-0 flex items-center justify-center px-10">
                            @php
                                $logoUrl = $brand->logo_path;
                                if (!str_starts_with($logoUrl, 'http')) {
                                    $logoUrl = asset('storage/' . ltrim($logoUrl, '/'));
                                }
                            @endphp
                            <img src="{{ $logoUrl }}"
                                 class="h-8 md:h-10 w-auto object-contain"
                                 style="opacity: 0.85;"
                                 loading="lazy"
                                 alt="{{ $brand->name }}">
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- Borde amarillo bottom --}}
        <div class="absolute bottom-0 left-0 right-0 h-[3px]" style="background: #F5C400;"></div>
    </div>
    @endif

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="max-w-7xl mx-auto px-4 py-32 space-y-48" style="background: #F0EDE8;">

        {{-- SOBRE EL EVENTO --}}
        <section id="about" class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-10">
            <div class="space-y-8">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-3 block" style="color: #1A6FBF;">
                        ● {{ $event->getSetting('about_subtitle', 'Sobre el Evento') }}
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                        {!! nl2br(e($event->getSetting('about_title', 'Una competencia familiar y emocionante'))) !!}
                    </h2>
                </div>
                <div class="text-slate-600 leading-relaxed text-base font-medium">
                    {!! nl2br(e($event->description ?: 'Pronto tendremos más detalles sobre este emocionante evento.')) !!}
                </div>
                @if($event->participants->where('is_active', true)->count() > 0 || $event->judges->where('is_active', true)->count() > 0)
                <div class="flex gap-6 sm:gap-10 pt-6 border-t border-slate-200 overflow-x-auto">
                    @if($event->participants->where('is_active', true)->count() > 0)
                    <div class="shrink-0 text-center sm:text-left">
                        <span class="text-2xl sm:text-4xl font-black block" style="color: #1A6FBF;">{{ $event->participants->where('is_active', true)->count() }}</span>
                        <span class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Participantes</span>
                    </div>
                    <div class="w-px bg-slate-200 shrink-0"></div>
                    @endif
                    
                    @if($event->judges->where('is_active', true)->count() > 0)
                    <div class="shrink-0 text-center sm:text-left">
                        <span class="text-2xl sm:text-4xl font-black block" style="color: #1A6FBF;">{{ $event->judges->where('is_active', true)->count() }}</span>
                        <span class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Jueces</span>
                    </div>
                    <div class="w-px bg-slate-200 shrink-0"></div>
                    @endif

                    <div class="shrink-0 text-center sm:text-left">
                        <span class="text-2xl sm:text-4xl font-black block" style="color: #F5C400;">{{ $event->date->format('Y') }}</span>
                        <span class="text-[9px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">Edición</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Galería Highlights --}}
            <div class="grid grid-cols-2 gap-4">
                @php 
                    $highlights = $event->getMedia('gallery_photos')
                        ->filter(fn($m) => $m->getCustomProperty('highlight') === true)
                        ->take(3);
                    
                    if($highlights->count() === 0) {
                        $highlights = $event->getMedia('gallery_photos')->take(3);
                    }
                @endphp

                @foreach($highlights as $index => $media)
                    <div class="aspect-square rounded-[2rem] overflow-hidden shadow-lg {{ $index == 1 ? 'mt-8' : ($index == 2 ? '-mt-4' : '') }}">
                        <img src="{{ str_replace('http://', 'https://', $media->getUrl()) }}"
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-700 cursor-pointer"
                             @click="selectedPhoto = '{{ str_replace('http://', 'https://', $media->getUrl()) }}'; document.body.style.overflow = 'hidden';">
                    </div>
                    
                    @if($index == 0)
                        <a href="#full-gallery" class="aspect-square rounded-[2rem] flex items-center justify-center border-2 border-dashed border-slate-200 hover:border-sky-300 hover:bg-sky-50 transition-all group">
                            <span class="text-slate-300 group-hover:text-sky-500 font-black text-xs uppercase tracking-widest">Ver Galería</span>
                        </a>
                    @endif
                @endforeach

                @if($highlights->count() == 0)
                    <div class="aspect-square rounded-[2rem] bg-white shadow-sm border border-slate-100"></div>
                    <div class="aspect-square rounded-[2rem] mt-8 flex items-center justify-center border-2 border-dashed border-slate-200">
                        <span class="text-slate-300 font-black text-xs uppercase tracking-widest">Galería</span>
                    </div>
                    <div class="aspect-square rounded-[2rem] -mt-4 border border-slate-100 bg-white/60 shadow-sm"></div>
                    <div class="aspect-square rounded-[2rem] mt-8 bg-white shadow-sm border border-slate-100"></div>
                @endif
            </div>
        </section>

        {{-- CRONOGRAMA --}}
        @if($schedule = $event->getSetting('event_schedule'))
        <section id="schedule" class="py-10 bg-white rounded-[3rem] p-10 md:p-16 shadow-xl border border-slate-100">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <span class="text-xs font-black uppercase tracking-[0.3em] block" style="color: #F5C400;">
                        ● Planificación
                    </span>
                    <h2 class="text-4xl font-black text-slate-800 tracking-tight italic">
                        Cronograma <br>
                        <span style="color: #1A6FBF;">del Evento</span>
                    </h2>
                    <p class="text-slate-500 font-medium tracking-tight">
                        Sigue cada detalle de nuestra agenda para no perderte nada.
                    </p>
                </div>
                <div class="lg:col-span-2">
                    <div class="prose prose-slate max-w-none font-bold text-slate-700 whitespace-pre-line bg-slate-50 p-8 rounded-2xl border border-slate-100 shadow-inner">
                        {!! $schedule !!}
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- TABLA DE POSICIONES --}}
        @if($event->participants->where('is_active', true)->count() > 0 && $event->getSetting('show_leaderboard_to_participants') === 'true' && isset($ranking) && $ranking->count() > 0)
        <section id="ranking" class="py-10">
            <div class="text-center mb-12 space-y-3">
                <span class="text-xs font-black uppercase tracking-[0.3em] block" style="color: #1A6FBF;">
                    ● Resultados en Tiempo Real
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
                    Tabla de <span style="color: #1A6FBF;">Posiciones</span>
                </h2>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden shadow-lg border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr style="background: #1A6FBF;">
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-white/70 tracking-widest w-16">#</th>
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-white/70 tracking-widest">Participante</th>
                                <th class="px-6 py-5 text-[10px] font-black uppercase text-white tracking-widest text-right">Puntos Totales</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($ranking->take(10) as $index => $row)
                                @php
                                    $photoUrl = $row['participant']->photo_path;
                                    if ($photoUrl && !str_starts_with($photoUrl, 'http')) {
                                        $photoUrl = secure_asset('storage/' . ltrim($photoUrl, '/'));
                                    } elseif ($photoUrl) {
                                        $photoUrl = str_replace('http://', 'https://', $photoUrl);
                                    }
                                @endphp
                                <tr class="group hover:bg-blue-50/50 transition-all duration-200">
                                    <td class="px-6 py-5">
                                        @if($index == 0)
                                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-lg shadow-md"
                                                 style="background: #F5C400; color: #0d3a6e;">1</div>
                                        @elseif($index == 1)
                                            <div class="w-10 h-10 bg-slate-200 text-slate-700 rounded-xl flex items-center justify-center font-black text-base">2</div>
                                        @elseif($index == 2)
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-base text-white"
                                                 style="background: #b45309;">3</div>
                                        @else
                                            <div class="text-slate-400 font-bold text-sm pl-2">#{{ $index + 1 }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}"
                                                     class="w-10 h-10 rounded-xl object-cover border-2 border-slate-100 group-hover:border-blue-200 transition-colors shrink-0">
                                            @endif
                                            <div>
                                                <div class="font-black text-slate-800 text-sm tracking-tight group-hover:text-blue-700 transition-colors">
                                                    {{ $row['participant']->name }}
                                                </div>
                                                <div class="text-[9px] font-bold uppercase tracking-widest text-slate-400">
                                                    {{ $row['participant']->category }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-xl font-black" style="color: #1A6FBF;">
                                            {{ number_format($row['total_score'], 1) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="text-center mt-4 text-[10px] text-slate-400 font-black uppercase tracking-[0.3em]">
                Los resultados se actualizan automáticamente
            </p>
        </section>
        @endif

        {{-- COMPETIDORES --}}
        @if($event->participants->where('is_active', true)->count() > 0)
        <section id="contestants" class="py-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12 gap-6">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-3 block" style="color: #1A6FBF;">
                        ● Los Protagonistas
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
                        Competidores <span class="text-slate-400">Inscritos</span>
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($event->participants->where('is_active', true) as $participant)
                    @php
                        $partPhotoUrl = $participant->photo_path;
                        if ($partPhotoUrl && !str_starts_with($partPhotoUrl, 'http')) {
                            $partPhotoUrl = asset('storage/' . ltrim($partPhotoUrl, '/'));
                        }
                    @endphp
                    <div class="group relative aspect-[3/4] rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 bg-white border border-slate-100">
                        @if($partPhotoUrl)
                            <img src="{{ $partPhotoUrl }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 alt="{{ $participant->name }}">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center"
                                 style="background: linear-gradient(135deg, #1A6FBF, #0d3a6e);">
                                <span class="text-white/20 font-black text-4xl uppercase">{{ substr($participant->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent group-hover:via-slate-900/40 transition-all"></div>
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-wider text-white"
                                  style="background: rgba(26,111,191,0.85); backdrop-filter: blur(8px);">
                                {{ $participant->category }}
                            </span>
                        </div>
                        <div class="absolute bottom-5 left-4 right-4 space-y-2">
                            <h3 class="text-base md:text-xl font-black text-white uppercase tracking-tight leading-tight">
                                {{ $participant->name }}
                            </h3>
                            @if($event->getSetting('enable_public_vote') === 'true')
                                <button @click="castVote({{ $participant->id }})"
                                        :disabled="voting"
                                        class="w-full h-10 rounded-xl font-black text-xs uppercase tracking-widest transition-all transform active:scale-95 shadow-lg"
                                        style="background: #F5C400; color: #0d3a6e;">
                                    <span x-show="!voting">Votar</span>
                                    <span x-show="voting">...</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- JUECES --}}
        @if($event->judges->where('is_active', true)->count() > 0)
        <section id="judges" class="py-10">
            <div class="text-center mb-14 max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-black uppercase tracking-[0.3em] block" style="color: #1A6FBF;">
                    ● Panel de Evaluación
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
                    Nuestros <span style="color: #1A6FBF;">Jueces</span>
                </h2>
                <p class="text-slate-500 font-medium">
                    Expertos encargados de evaluar cada detalle de la competencia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($event->judges->where('is_active', true) as $judge)
                    @php
                        $judgePhotoUrl = $judge->user->photo_path ?? null;
                        if ($judgePhotoUrl && !str_starts_with($judgePhotoUrl, 'http')) {
                            $judgePhotoUrl = asset('storage/' . ltrim($judgePhotoUrl, '/'));
                        }
                    @endphp
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-blue-100 transition-all duration-300 group text-center">
                        <div class="relative w-24 h-24 mx-auto mb-5">
                            <div class="absolute inset-0 rounded-full blur-xl opacity-0 group-hover:opacity-30 transition-opacity"
                                 style="background: #1A6FBF;"></div>
                            <div class="relative w-full h-full rounded-full overflow-hidden border-4 border-slate-100 group-hover:border-blue-200 transition-colors">
                                @if($judgePhotoUrl)
                                    <img src="{{ $judgePhotoUrl }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white font-black text-2xl"
                                         style="background: linear-gradient(135deg, #1A6FBF, #0d3a6e);">
                                        {{ substr($judge->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $judge->user->name }}</h3>
                        <span class="inline-block mt-2 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white"
                              style="background: #1A6FBF;">
                            {{ $judge->specialty }}
                        </span>
                        <p class="mt-4 text-slate-500 text-sm leading-relaxed">
                            {{ $judge->user->bio ?: 'Experto reconocido con años de trayectoria en competencias de alto nivel.' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($allPhotos->count() > 0)
        <section id="full-gallery" class="py-10 space-y-12">
            <div class="text-center space-y-4">
                <span class="text-xs font-black uppercase tracking-[0.3em] block text-sky-500">
                    ● Galería del Evento
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
                    Nuestros <span class="text-sky-500">Momentos</span>
                </h2>
                <p class="text-slate-500 font-medium max-w-xl mx-auto">
                    Explora todos los detalles capturados durante la competencia. 
                </p>
            </div>

            <div class="masonry-gallery gap-4 md:gap-6">
                @foreach($allPhotos as $index => $photo)
                    <div class="break-inside-avoid group relative rounded-[2rem] overflow-hidden bg-slate-200 shadow-lg cursor-zoom-in border border-slate-100 mb-6 aspect-square"
                         @click="openLightbox({{ $index }})">
                        <img src="{{ $photo->getUrl() }}" 
                             loading="lazy"
                             class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-105 opacity-0 blur-lg" 
                             onload="this.classList.remove('opacity-0', 'blur-lg')"
                             alt="Foto del evento">
                        
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end justify-center pb-6">
                            <div class="bg-white/20 backdrop-blur-md rounded-full p-3 border border-white/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- LIGHTBOX --}}
        <div x-show="selectedPhoto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/98 backdrop-blur-xl p-4 md:p-10"
             @click.self="closeLightbox()"
             style="display: none;">
            
            {{-- Download --}}
            <a :href="selectedPhoto" 
               download
               class="absolute top-6 left-6 text-white/50 hover:text-white transition-all z-[120] bg-white/10 hover:bg-white/20 p-3 rounded-full backdrop-blur-md border border-white/10 group flex items-center gap-2 pr-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span class="text-[10px] font-black uppercase tracking-widest">Descargar Foto</span>
            </a>

            {{-- Close --}}
            <button @click="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-white transition-all z-[120] bg-white/10 hover:bg-white/20 p-3 rounded-full backdrop-blur-md border border-white/10 group">
                <svg class="w-8 h-8 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Nav Arrows --}}
            <button @click="prevPhoto()" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-white/30 hover:text-white transition-all z-[110] bg-white/5 hover:bg-white/10 p-3 md:p-5 rounded-full backdrop-blur-sm">
                <svg class="w-6 h-6 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button @click="nextPhoto()" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-white/30 hover:text-white transition-all z-[110] bg-white/5 hover:bg-white/10 p-3 md:p-5 rounded-full backdrop-blur-sm">
                <svg class="w-6 h-6 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Image Info --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/30 font-black text-[10px] uppercase tracking-[0.3em] z-[110] bg-black/20 px-6 py-2 rounded-full backdrop-blur-md border border-white/5">
                Fotografía <span class="text-white" x-text="currentIndex + 1"></span> <span class="mx-2 opacity-50">/</span> <span class="text-white" x-text="allPhotos.length"></span>
            </div>

            <img :src="selectedPhoto" 
                 class="max-w-[95vw] max-h-[80vh] md:max-w-[90vw] md:max-h-[85vh] object-contain rounded-xl shadow-2xl transition-all duration-500 border border-white/10"
                 @click.stop>
        </div>

    </main>
</div>

@endsection

@push('styles')
<style>
    /* Ticker de sponsors */
    .ticker-track {
        animation: ticker-scroll 50s linear infinite;
        will-change: transform;
    }
    @keyframes ticker-scroll {
        0%   { transform: translate3d(0, 0, 0); }
        100% { transform: translate3d(-50%, 0, 0); }
    }

    /* Animación del banner hero */
    @keyframes subtle-zoom {
        0%   { transform: scale(1.05); }
        100% { transform: scale(1.12); }
    }

    /* Galería masonry */
    .masonry-gallery {
        column-count: 2;
        column-gap: 1.5rem;
    }
    .masonry-gallery > div {
        break-inside: avoid;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) {
        .masonry-gallery { column-count: 3; }
    }
    @media (min-width: 1024px) {
        .masonry-gallery { column-count: 4; }
    }
    @media (min-width: 1280px) {
        .masonry-gallery { column-count: 5; }
    }
</style>
@endpush

@push('scripts')
{{-- Event Landing Logic has been moved to the top of the file for better initialization --}}
@endpush