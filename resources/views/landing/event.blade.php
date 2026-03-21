@extends('layouts.public')

@section('title', $event->name . ' | Super Carnes Eventos')

@section('content')
<div x-data="eventLanding('{{ $event->date->toISOString() }}')">

    {{-- ===================== HERO ===================== --}}
    <section class="relative h-[65vh] md:h-[85vh] flex items-end justify-center overflow-hidden">

        {{-- Banner --}}
        <div class="absolute inset-0 z-0">
            @if($event->banner_path)
                @php
                    $bannerUrl = $event->banner_path;
                    // Si la ruta no tiene http/https, usamos secure_asset para forzar HTTPS
                    if (!str_starts_with($bannerUrl, 'http')) {
                        $bannerUrl = secure_asset('storage/' . ltrim($bannerUrl, '/'));
                    } else {
                        // Si ya trae http://, lo forzamos a https:// para evitar el Mixed Content
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

            <h1 class="text-5xl md:text-8xl font-black uppercase leading-none text-white tracking-tighter drop-shadow-2xl">
                {{ $event->name }}
            </h1>

            <p class="text-lg md:text-2xl font-bold uppercase tracking-[0.2em]" style="color: #F5C400;">
    {{ $event->date->format('d M, Y') }} · {{ $event->date->format('g:i a') }}
</p>

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

    {{-- ===================== CARRUSEL SPONSORS ===================== --}}
    @if($event->brands->count() > 0)
    <div class="relative overflow-hidden py-5 border-b border-slate-200 group" style="background: #1A6FBF;">

        {{-- Borde amarillo top --}}
        <div class="absolute top-0 left-0 right-0 h-[3px]" style="background: #F5C400;"></div>

        <div class="overflow-hidden">
            <div class="flex items-center"
                 style="animation: scroll 30s linear infinite; width: max-content; white-space: nowrap;">
                @foreach($event->brands as $brand)
                    <div class="shrink-0 flex items-center justify-center px-12">
                        @php
                            $logoUrl = $brand->logo_path;
                            if (!str_starts_with($logoUrl, 'http')) {
                                $logoUrl = asset('storage/' . ltrim($logoUrl, '/'));
                            }
                        @endphp
                        <img src="{{ $logoUrl }}"
                             class="h-10 md:h-12 w-auto object-contain transition-all duration-500"
                             style="filter: brightness(0) invert(1); opacity: 0.7;"
                             onmouseover="this.style.opacity='1'"
                             onmouseout="this.style.opacity='0.7'"
                             alt="{{ $brand->name }}">
                    </div>
                @endforeach
                @foreach($event->brands as $brand)
                    <div class="shrink-0 flex items-center justify-center px-12">
                        @php
                            $logoUrl = $brand->logo_path;
                            if (!str_starts_with($logoUrl, 'http')) {
                                $logoUrl = asset('storage/' . ltrim($logoUrl, '/'));
                            }
                        @endphp
                        <img src="{{ $logoUrl }}"
                             class="h-10 md:h-12 w-auto object-contain transition-all duration-500"
                             style="filter: brightness(0) invert(1); opacity: 0.7;"
                             onmouseover="this.style.opacity='1'"
                             onmouseout="this.style.opacity='0.7'"
                             alt="{{ $brand->name }}">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Borde amarillo bottom --}}
        <div class="absolute bottom-0 left-0 right-0 h-[3px]" style="background: #F5C400;"></div>
    </div>
    @endif

    {{-- ===================== MAIN CONTENT ===================== --}}
    <main class="max-w-7xl mx-auto px-4 py-20 space-y-28" style="background: #F0EDE8;">

        {{-- SOBRE EL EVENTO --}}
        <section id="about" class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.3em] mb-3 block" style="color: #1A6FBF;">
                        ● Sobre el Evento
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
                        Una competencia <br>
                        <span style="color: #1A6FBF;">familiar y emocionante</span>
                    </h2>
                </div>
                <div class="text-slate-600 leading-relaxed text-base font-medium">
                    {!! nl2br(e($event->description ?: 'Pronto tendremos más detalles sobre este emocionante evento.')) !!}
                </div>
                <div class="flex gap-10 pt-6 border-t border-slate-200">
                    <div>
                        <span class="text-4xl font-black block" style="color: #1A6FBF;">{{ $event->participants->count() }}</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Participantes</span>
                    </div>
                    <div class="w-px bg-slate-200"></div>
                    <div>
                        <span class="text-4xl font-black block" style="color: #1A6FBF;">{{ $event->judges->count() }}</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jueces</span>
                    </div>
                    <div class="w-px bg-slate-200"></div>
                    <div>
                        <span class="text-4xl font-black block" style="color: #F5C400;">{{ $event->date->format('Y') }}</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Edición</span>
                    </div>
                </div>
            </div>

            {{-- Galería --}}
            <div class="grid grid-cols-2 gap-4">
                @php $gallery = $event->getMedia('gallery'); @endphp
                @if($gallery->count() > 0)
                    @foreach($gallery->take(4) as $index => $media)
                        <div class="aspect-square rounded-[2rem] overflow-hidden shadow-lg {{ $index % 2 != 0 ? 'mt-8' : '' }}">
                            <img src="{{ $media->getUrl() }}"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                        </div>
                    @endforeach
                @else
                    <div class="aspect-square rounded-[2rem] bg-white shadow-sm border border-slate-100"></div>
                    <div class="aspect-square rounded-[2rem] mt-8 flex items-center justify-center border-2 border-dashed border-slate-200">
                        <span class="text-slate-300 font-black text-xs uppercase tracking-widest">Galería</span>
                    </div>
                    <div class="aspect-square rounded-[2rem] -mt-4 border border-slate-100 bg-white/60 shadow-sm"></div>
                    <div class="aspect-square rounded-[2rem] mt-8 bg-white shadow-sm border border-slate-100"></div>
                @endif
            </div>
        </section>

        {{-- TABLA DE POSICIONES --}}
        @if($event->getSetting('show_leaderboard_to_participants') === 'true' && $ranking && $ranking->count() > 0)
        <section id="ranking">
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
                                        // Aquí también aplicamos secure_asset para evitar el Mixed Content del que hablamos antes
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
        <section id="contestants">
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
                @foreach($event->participants as $participant)
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

        {{-- JUECES --}}
        @if($event->judges->count() > 0)
        <section id="judges">
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
                @foreach($event->judges as $judge)
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

    </main>
</div>
@endsection

@push('styles')
<style>
    @keyframes scroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    @keyframes subtle-zoom {
        0%   { transform: scale(1.05); }
        100% { transform: scale(1.12); }
    }
</style>
@endpush

@push('scripts')
<script>
function eventLanding(targetDate) {
    return {
        target: new Date(targetDate),
        countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
        eventStarted: false,
        voting: false,
        fingerprint: null,

        async init() {
            const fp = await FingerprintJS.load();
            const result = await fp.get();
            this.fingerprint = result.visitorId;
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
