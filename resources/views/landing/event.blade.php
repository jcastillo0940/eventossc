@extends('layouts.public')

@section('title', $event->name . ' - Evento Pro')

@section('content')
<div x-data="eventLanding('{{ $event->date->toISOString() }}')">
    <!-- Hero Section -->
    <section class="relative h-[60vh] md:h-[80vh] flex items-end justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/20 to-transparent z-10"></div>
        <div class="absolute inset-0 bg-slate-900 shadow-2xl z-0">
            @if($event->banner_path)
                <img src="{{ Storage::url($event->banner_path) }}" class="w-full h-full object-cover transition-transform duration-[5s] scale-100 animate-[pulse_10s_infinite]" alt="{{ $event->name }}">
            @endif
        </div>
        
        <div class="relative z-20 text-center px-4 pb-20 max-w-5xl mx-auto space-y-6">
            <div class="inline-flex items-center gap-3 glass px-6 py-2 rounded-full border border-sky-400/30 text-sky-400 text-xs font-black uppercase tracking-widest mb-6">
                <span class="w-2 h-2 bg-sky-400 rounded-full animate-ping"></span>
                LIVE EVENT
            </div>
            <h1 class="text-5xl md:text-9xl font-black italic uppercase leading-none text-white tracking-tighter mix-blend-difference">
                {{ $event->name }}
            </h1>
            <p class="text-xl md:text-3xl text-sky-400 font-bold uppercase tracking-[0.2em] italic italic">{{ $event->date->format('d M, Y') }} | {{ $event->date->format('H:i') }} HS</p>
            
            <!-- Countdown Section -->
            <template x-if="!eventStarted">
                <div class="grid grid-cols-4 gap-4 md:gap-8 max-w-2xl mx-auto mt-12 bg-slate-900/50 backdrop-blur-xl p-8 rounded-[2rem] border border-slate-700/50">
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.days">00</span>
                        <span class="text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest">Días</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.hours">00</span>
                        <span class="text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest">Horas</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block" x-text="countdown.minutes">00</span>
                        <span class="text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest">Mins</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl md:text-5xl font-black text-white block text-sky-400" x-text="countdown.seconds">00</span>
                        <span class="text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest">Segs</span>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <!-- Sponsor Carousel -->
    <div class="bg-slate-900 border-y border-slate-800 py-10 relative overflow-hidden group">
        <div class="flex animate-[scroll_30s_linear_infinite] whitespace-nowrap min-w-full gap-24 items-center">
            @foreach($event->brands as $brand)
                <div class="flex-shrink-0 flex items-center gap-4 grayscale group-hover:grayscale-0 transition-all">
                   <img src="{{ Storage::url($brand->logo_path) }}" class="h-16 md:h-24 w-auto object-contain opacity-50 group-hover:opacity-100 transition-opacity" alt="{{ $brand->name }}">
                </div>
            @endforeach
            <!-- Duplicate for infinite -->
            @foreach($event->brands as $brand)
                <div class="flex-shrink-0 flex items-center gap-4 grayscale group-hover:grayscale-0 transition-all">
                   <img src="{{ Storage::url($brand->logo_path) }}" class="h-16 md:h-24 w-auto object-contain opacity-50 group-hover:opacity-100 transition-opacity" alt="{{ $brand->name }}">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-32 space-y-40">
        
        <!-- About Section -->
        <section id="about" class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div class="space-y-10">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.4em] text-indigo-400 mb-4 block">SOBRE EL EVENTO</span>
                    <h2 class="text-4xl md:text-6xl font-bold text-white tracking-tight leading-tight">La competencia que redefine el <span class="italic underline decoration-sky-400">talento</span></h2>
                </div>
                <div class="prose prose-invert prose-lg text-slate-400 leading-relaxed font-medium italic italic">
                    {!! nl2br(e($event->description ?: 'No hay descripción disponible para este evento. Pronto tendremos más detalles para compartir contigo.')) !!}
                </div>
                <!-- Mini Stats maybe? -->
                <div class="flex gap-12 pt-8 border-t border-slate-800">
                    <div>
                        <span class="text-4xl font-black text-white block">{{ $event->participants->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Participantes</span>
                    </div>
                    <div>
                        <span class="text-4xl font-black text-white block">{{ $event->judges->count() }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Jueces Pro</span>
                    </div>
                </div>
            </div>
            
            <!-- Gallery Column -->
            <div class="grid grid-cols-2 gap-4">
                @php $gallery = $event->getMedia('gallery'); @endphp
                @if($gallery->count() > 0)
                    @foreach($gallery->take(4) as $index => $media)
                        <div class="aspect-square rounded-[2rem] overflow-hidden glass {{ $index % 2 != 0 ? 'mt-12' : '' }}">
                            <img src="{{ $media->getUrl() }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-700">
                        </div>
                    @endforeach
                @else
                    <!-- Fallback if no gallery -->
                    <div class="aspect-square rounded-[2rem] bg-slate-800/50"></div>
                    <div class="aspect-square rounded-[2rem] bg-indigo-500/10 mt-12 flex items-center justify-center italic text-slate-600 font-black">GALLERY SPACE</div>
                    <div class="aspect-square rounded-[2rem] bg-sky-500/10 -mt-6"></div>
                    <div class="aspect-square rounded-[2rem] bg-slate-800/50 mt-12"></div>
                @endif
            </div>
        </section>

        <!-- Ranking / Leaderboard Section -->
        @if($ranking && $ranking->count() > 0)
        <section id="ranking" class="relative">
            <div class="absolute inset-0 bg-sky-500/5 blur-[100px] -z-10"></div>
            <div class="text-center mb-16 space-y-4">
                <span class="text-xs font-black uppercase tracking-[0.4em] text-sky-400 block font-bold italic italic">RESULTADOS EN TIEMPO REAL</span>
                <h2 class="text-5xl md:text-7xl font-black text-white italic italic uppercase tracking-tighter">Tabla de <span class="text-slate-500">Posiciones</span></h2>
            </div>
            
            <div class="glass p-1 rounded-[3rem] overflow-hidden border-slate-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/50">
                            <tr>
                                <th class="px-10 py-8 text-[10px] font-black uppercase text-slate-500 tracking-widest">Posición</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase text-slate-500 tracking-widest">Participante</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase text-slate-500 tracking-widest text-center">Jurado</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase text-slate-500 tracking-widest text-center">Público / Social</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase text-sky-400 tracking-widest text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($ranking->take(10) as $index => $row)
                                <tr class="group hover:bg-white/5 transition-all duration-300">
                                    <td class="px-10 py-8">
                                        <div class="flex items-center gap-4">
                                            @if($index == 0)
                                                <div class="w-12 h-12 bg-amber-400 text-amber-900 rounded-2xl flex items-center justify-center font-black text-xl shadow-xl shadow-amber-400/20">1</div>
                                            @elseif($index == 1)
                                                <div class="w-10 h-10 bg-slate-300 text-slate-900 rounded-xl flex items-center justify-center font-black text-lg">2</div>
                                            @elseif($index == 2)
                                                <div class="w-10 h-10 bg-amber-700 text-white rounded-xl flex items-center justify-center font-black text-lg">3</div>
                                            @else
                                                <div class="text-slate-500 font-bold ml-4">#{{ $index + 1 }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-10 py-8 font-black uppercase tracking-tight">
                                        <div class="flex items-center gap-6">
                                            @if($row['participant']->photo_path)
                                                <img src="{{ Storage::url($row['participant']->photo_path) }}" class="w-14 h-14 rounded-full object-cover border-2 border-slate-800 group-hover:border-sky-400/50 transition-colors">
                                            @endif
                                            <div>
                                                <div class="text-white text-lg group-hover:text-sky-400 transition-colors italic italic tracking-tighter">{{ $row['participant']->name }}</div>
                                                <div class="text-[9px] text-slate-600 font-black tracking-widest">{{ $row['participant']->category }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-8 text-center">
                                        <div class="text-lg font-bold text-slate-400 italic italic">{{ number_format($row['judge_score'], 1) }}</div>
                                    </td>
                                    <td class="px-10 py-8 text-center text-slate-500 font-medium">
                                        <div class="text-xs uppercase tracking-tighter">
                                            {{ $row['public_votes'] }} Votos + {{ $row['social_score'] }} Soc.
                                        </div>
                                    </td>
                                    <td class="px-10 py-8 text-right">
                                        <div class="text-3xl font-black text-white italic italic group-hover:scale-110 transition-transform origin-right">{{ number_format($row['total_score'], 1) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="text-center mt-8 text-[10px] text-slate-600 font-black uppercase tracking-[0.4em] italic italic">LOS RESULTADOS SE ACTUALIZAN AUTOMÁTICAMENTE SEGÚN LA CARGA DE PUNTOS</p>
        </section>
        @endif

        <!-- Contestants Grid -->
        <section id="contestants">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-20 gap-8">
                <div>
                    <span class="text-xs font-black uppercase tracking-[0.4em] text-sky-400 mb-4 block">LOS PROTAGONISTAS</span>
                    <h2 class="text-4xl md:text-6xl font-black text-white italic italic">Competidores <span class="text-slate-500 tracking-tighter">Inscritos</span></h2>
                </div>
                <div class="hidden md:block h-[1px] flex-grow mx-12 bg-slate-800"></div>
                <a href="#voting" class="glass px-8 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest hover:bg-white hover:text-indigo-900 transition-all italic italic">Filtrar por Categoría</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($event->participants as $participant)
                    <div class="group relative aspect-[3/4] rounded-[3rem] overflow-hidden glass border-slate-800 hover:border-sky-400/50 transition-all duration-500 hover:-translate-y-4">
                        @if($participant->photo_path)
                            <img src="{{ Storage::url($participant->photo_path) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $participant->name }}">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent group-hover:via-slate-950/40 transition-all"></div>
                        
                        <div class="absolute bottom-8 left-8 right-8 space-y-4">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-sky-400 mb-1 block">{{ $participant->category }}</span>
                                <h3 class="text-2xl font-black text-white italic italic tracking-tight mb-2 uppercase">{{ $participant->name }}</h3>
                            </div>
                            
                            @if($event->getSetting('enable_public_vote') === 'true')
                                <button @click="castVote({{ $participant->id }})" 
                                        :disabled="voting"
                                        class="w-full bg-white text-indigo-900 h-14 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-sky-400 hover:text-white transition-all transform active:scale-95 shadow-2xl">
                                    <span x-show="!voting">VOTAR AHORA</span>
                                    <span x-show="voting">PROCESANDO...</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Judges Section -->
        <section id="judges">
            <div class="text-center mb-24 max-w-2xl mx-auto space-y-4">
                <span class="text-xs font-black uppercase tracking-[0.4em] text-indigo-400 block font-bold">CALIDAD PROFESIONAL</span>
                <h2 class="text-5xl md:text-7xl font-bold text-white tracking-tighter">El <span class="text-indigo-500 italic">Panel</span> de Jueces</h2>
                <p class="text-lg text-slate-500 font-medium">Expertos de la industria encargados de evaluar cada detalle técnico y artístico de la competencia.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach($event->judges as $judge)
                    <div class="glass p-10 rounded-[4rem] border-slate-800 hover:border-indigo-500/50 transition-all group lg:first:mt-12 lg:last:mt-12">
                        <div class="relative w-40 h-40 mx-auto mb-10">
                            <div class="absolute inset-0 bg-indigo-500 rounded-full blur-2xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                            <div class="relative rounded-full overflow-hidden w-full h-full border-2 border-slate-700 p-2">
                                @if($judge->user->photo_path)
                                    <img src="{{ Storage::url($judge->user->photo_path) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-slate-800 flex items-center justify-center text-slate-600 font-black italic italic">PRO</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-center space-y-6">
                            <div>
                                <h3 class="text-2xl font-black text-white uppercase tracking-tight italic italic">{{ $judge->user->name }}</h3>
                                <span class="px-4 py-1 glass rounded-full text-[10px] font-black text-indigo-400 uppercase tracking-widest border border-indigo-400/20 block w-max mx-auto mt-2">
                                    {{ $judge->specialty }}
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed italic italic font-medium">
                                {{ $judge->user->bio ?: 'Experto reconocido en la industria con años de trayectoria evaluando competencias de alto nivel.' }}
                            </p>
                            <!-- Social links? (Optional mockup) -->
                            <div class="flex justify-center gap-6 pt-4 text-slate-600">
                                <span class="hover:text-sky-400 transition-colors cursor-pointer">INSTAGRAM</span>
                                <span class="hover:text-sky-400 transition-colors cursor-pointer">LINKEDIN</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>
</div>

@endsection

@push('styles')
<style>
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
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
                // Initialize Fingerprint
                const fp = await FingerprintJS.load();
                const result = await fp.get();
                this.fingerprint = result.visitorId;

                // Start countdown
                this.updateCountdown();
                setInterval(() => this.updateCountdown(), 1000);
            },

            updateCountdown() {
                const now = new Date();
                const diff = this.target - now;

                if (diff <= 0) {
                    this.eventStarted = true;
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                this.countdown.days = d.toString().padStart(2, '0');
                this.countdown.hours = h.toString().padStart(2, '0');
                this.countdown.minutes = m.toString().padStart(2, '0');
                this.countdown.seconds = s.toString().padStart(2, '0');
            },

            async castVote(participantId) {
                if (!this.fingerprint) {
                    window.$toast?.warning("Cargando...", "El sistema de seguridad se está iniciando, intenta de nuevo.");
                    return;
                }

                if (!confirm("¿Deseas registrar tu voto para esta categoría? Solo se permite un voto por persona.")) return;

                this.voting = true;

                try {
                    const res = await fetch('{{ route('public.vote') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            event_id: {{ $event->id }},
                            participant_id: participantId,
                            fingerprint: this.fingerprint
                        })
                    });

                    const data = await res.json();
                    
                    if (res.ok) {
                        window.$toast?.success("¡Voto registrado!", "Gracias por participar en la elección.");
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        window.$toast?.error("No se pudo votar", data.error || "Es posible que ya hayas registrado un voto.");
                    }
                } catch (e) {
                    window.$toast?.error("Error de conexión", "No pudimos conectar con el servidor. Reintenta pronto.");
                } finally {
                    this.voting = false;
                }
            }
        }
    }
</script>
@endpush
