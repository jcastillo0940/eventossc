<style>
    .perspective-1000 { perspective: 1000px; }
    .flip-card-inner {
        transform-style: preserve-3d;
        transition: transform 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }
    .flip-card-reveal { transform: rotateY(180deg); }
    .flip-card-front, .flip-card-back {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        position: absolute;
        inset: 0;
        border-radius: 2.5rem;
        overflow: hidden;
    }
    .flip-card-back { transform: rotateY(180deg); }
    .video-background {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
        overflow: hidden; z-index: 0; pointer-events: none;
    }
    .video-background iframe {
        position: absolute; top: 50%; left: 50%;
        width: 100vw; height: 100vh;
        transform: translate(-50%, -50%); pointer-events: none;
    }
    @media (min-aspect-ratio: 16/9) { .video-background iframe { height: 56.25vw; } }
    @media (max-aspect-ratio: 16/9) { .video-background iframe { width: 177.78vh; } }
</style>

<div class="w-screen h-screen overflow-hidden flex flex-col justify-between relative"
     x-data="{ celebrating: false }"
     wire:poll.3s="refreshRevealed"
     style="background-color: {{ $visuals['bg'] ?? '#020617' }};">

    @if($visuals['video_url'])
        <div class="video-background opacity-40">
            @php
                $videoId = $visuals['video_url'];
                if (str_contains($videoId, 'watch?v=')) { $videoId = explode('v=', $videoId)[1]; }
                if (str_contains($videoId, 'youtu.be/')) { $videoId = explode('youtu.be/', $videoId)[1]; }
                $videoId = explode('&', $videoId)[0];
            @endphp
            <iframe src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&controls=0&loop=1&playlist={{ $videoId }}&showinfo=0&rel=0&modestbranding=1"
                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    @endif

    @if($visuals['background_image'])
        <div class="absolute inset-0 bg-cover bg-center {{ $visuals['video_url'] ? 'opacity-20' : 'opacity-40' }} pointer-events-none"
             style="background-image: url('{{ $visuals['background_image'] }}');"></div>
    @endif

    <div class="absolute inset-0 opacity-20 pointer-events-none"
         style="background: radial-gradient(circle at center, {{ $visuals['accent'] ?? '#38bdf8' }} 0%, transparent 70%);"></div>

    <!-- HEADER -->
    <header class="relative z-10 w-full px-12 py-6 flex items-center justify-between h-[18vh] shrink-0">

        {{-- Izquierda: Logo del evento más grande --}}
        <div class="w-1/4 flex justify-start items-center">
            @if($event->logo_path)
                <img src="{{ $event->logo_path }}" alt="Event Logo" class="max-h-[14vh] object-contain drop-shadow-2xl">
            @endif
        </div>

        {{-- Centro: Nombre del evento --}}
        <div class="w-2/4 flex flex-col items-center justify-center text-center space-y-2 animate-pulse">
            <div class="inline-block px-8 py-2 bg-slate-900/50 backdrop-blur-md rounded-full border border-sky-400/30 text-sky-400 text-[10px] font-black uppercase tracking-[0.5em]">
                LIVE
            </div>
            <h1 class="text-5xl 2xl:text-7xl font-black italic uppercase tracking-tighter text-white drop-shadow-lg">
                {{ $event->name }}
            </h1>
        </div>

        {{-- Derecha: Logo Supercarnes --}}
        <div class="w-1/4 flex justify-end items-center">
            <img src="https://eventos.supercarnes.com/storage/14/logo-super-carnes.png"
                 alt="Supercarnes"
                 class="max-h-[10vh] object-contain drop-shadow-2xl opacity-80">
        </div>
    </header>

    <!-- PODIUM -->
    <div class="relative z-10 flex items-end justify-center w-full max-w-7xl mx-auto gap-8 2xl:gap-12 flex-1 pb-4">
        @php
            $podiumConfig = [
                2 => ['label' => '2º LUGAR', 'h' => 'h-[22vh]', 'color' => 'bg-slate-300', 'border' => '#cbd5e1'],
                1 => ['label' => '1º LUGAR', 'h' => 'h-[32vh]', 'color' => 'bg-amber-400', 'border' => '#fbbf24'],
                3 => ['label' => '3º LUGAR', 'h' => 'h-[16vh]', 'color' => 'bg-amber-700', 'border' => '#92400e'],
            ];
            $rankingList = collect($ranking);
        @endphp

        @foreach([2, 1, 3] as $pos)
            @php
                $winner = $rankingList->get($pos - 1);
                $config = $podiumConfig[$pos];
            @endphp

            <div class="flex flex-col items-center gap-6 perspective-1000 w-[280px] 2xl:w-[340px]">

                <div class="relative h-[30vh] 2xl:h-[35vh] w-full aspect-[3/4] flip-card-inner"
                     data-position="{{ $pos }}">

                    {{-- FRENTE: logo BBQ en vez de candado --}}
                    <div class="flip-card-front bg-slate-900/80 backdrop-blur-md flex flex-col items-center justify-center border-2 border-slate-700">
                        <img src="https://eventos.supercarnes.com/storage/11/bbgrs.png"
                             alt="BBQ Challenge"
                             class="w-28 h-28 object-contain opacity-30 drop-shadow-2xl">
                        <p class="mt-4 text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] text-center px-4">Esperando el anuncio...</p>
                    </div>

                    {{-- REVERSO: ganador --}}
                    <div class="flip-card-back bg-slate-900 border-4" style="border-color: {{ $config['border'] }};">
                        @if($winner && $winner['participant']->photo_path)
                            <img src="{{ Storage::url($winner['participant']->photo_path) }}" class="w-full h-full object-cover absolute inset-0">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                        <div class="absolute bottom-6 left-0 right-0 text-center px-4 space-y-1">
                            @if($winner)
                                <h3 class="text-3xl 2xl:text-4xl font-black text-white italic uppercase tracking-tighter leading-none">
                                    {{ $winner['participant']->name }}
                                </h3>
                                <div class="text-[9px] 2xl:text-[11px] font-black uppercase text-sky-400 tracking-widest">
                                    {{ $winner['participant']->category }}
                                </div>
                                <div class="pt-3 flex justify-center">
                                    <span class="bg-black/50 backdrop-blur-sm px-5 py-1.5 rounded-full text-xl 2xl:text-2xl font-black text-white italic tracking-tighter border border-white/20 shadow-lg">
                                        {{ number_format($winner['total_score'], 1) }} PTS
                                    </span>
                                </div>
                            @else
                                <h3 class="text-2xl font-black text-white italic uppercase">Sin datos</h3>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="w-full {{ $config['h'] }} {{ $config['color'] }} rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.3)] relative flex flex-col items-center justify-center overflow-hidden border-t-2 border-white/20">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/30 to-transparent opacity-50"></div>
                    <span class="relative text-[7vh] 2xl:text-[9vh] leading-none font-black text-white/30 italic -mb-4 drop-shadow-md">{{ $pos }}</span>
                    <span class="relative text-xs 2xl:text-lg font-black {{ $pos == 1 ? 'text-amber-900' : 'text-slate-800' }} uppercase tracking-[0.3em] drop-shadow-sm">{{ $config['label'] }}</span>
                </div>

            </div>
        @endforeach
    </div>

    <!-- FOOTER SPONSORS -->
    <footer class="relative z-20 w-full bg-slate-950/90 backdrop-blur-xl border-t-2 border-slate-800 flex items-center overflow-hidden h-[10vh] shrink-0">
        <div class="absolute left-0 top-0 bottom-0 bg-sky-600 z-30 px-8 flex items-center justify-center shadow-[10px_0_20px_rgba(0,0,0,0.5)] skew-x-[-10deg] -ml-4">
            <span class="font-black uppercase tracking-[0.3em] text-white text-sm skew-x-[10deg] ml-4">Gracias a:</span>
        </div>

        <div class="absolute inset-0 flex items-center overflow-hidden pl-40">
            <div id="ticker-track" class="flex items-center h-full" style="will-change: transform;">
                @foreach(range(0, 7) as $i)
                    @foreach($active_brands as $brand)
                        <div class="shrink-0 px-16 flex items-center justify-center">
                            <img src="{{ $brand->logo_path }}" alt="{{ $brand->name }}"
                                 class="h-[5vh] w-auto object-contain grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </footer>

    @script
    <script>
        function revealCard(position) {
            const card = document.querySelector(`.flip-card-inner[data-position="${position}"]`);
            if (card) card.classList.add('flip-card-reveal');
        }

        @foreach($revealed as $pos)
            revealCard({{ $pos }});
        @endforeach

        $wire.on('winner-celebration', (pos) => {
            const count = 300;
            const defaults = { origin: { y: 0.7 }, zIndex: 9999 };
            function fire(particleRatio, opts) {
                confetti({ ...defaults, ...opts, particleCount: Math.floor(count * particleRatio) });
            }
            fire(0.25, { spread: 26, startVelocity: 55 });
            fire(0.2,  { spread: 60 });
            fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
            fire(0.1,  { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
            fire(0.1,  { spread: 120, startVelocity: 45 });
        });

        window.Echo.channel('event.{{ $event->id }}').listen('.WinnerRevealed', (data) => {
            if (data.position === 0) {
                document.querySelectorAll('.flip-card-inner').forEach(card => {
                    card.classList.remove('flip-card-reveal');
                });
            } else {
                revealCard(data.position);
                $wire.handleWinnerRevealed(data);
            }
        });

        // Carrusel infinito con JS
        (function() {
            const track = document.getElementById('ticker-track');
            if (!track) return;
            const brandsCount = {{ $active_brands->count() }};
            let offset = 0;
            const speed = 0.8;

            function getGroupWidth() {
                let w = 0;
                for (let i = 0; i < brandsCount; i++) {
                    if (track.children[i]) w += track.children[i].offsetWidth;
                }
                return w;
            }

            function animate() {
                offset += speed;
                const groupWidth = getGroupWidth();
                if (groupWidth > 0 && offset >= groupWidth) {
                    offset -= groupWidth;
                }
                track.style.transform = `translateX(-${offset}px)`;
                requestAnimationFrame(animate);
            }

            requestAnimationFrame(animate);
        })();
    </script>
    @endscript
</div>
