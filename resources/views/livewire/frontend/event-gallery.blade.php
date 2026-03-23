<div x-data="{ 
    selectedPhoto: null,
    openLightbox(photoUrl) {
        this.selectedPhoto = photoUrl;
        document.body.style.overflow = 'hidden';
    },
    closeLightbox() {
        this.selectedPhoto = null;
        document.body.style.overflow = 'auto';
    }
}" @keydown.escape.window="closeLightbox()">
    
    {{-- PHOTOS SECTION --}}
    @if($photos->count() > 0)
    <div class="space-y-12">
        <div class="columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
            @foreach($photos as $photo)
                <div class="break-inside-avoid group relative rounded-[2rem] overflow-hidden bg-white shadow-lg cursor-zoom-in border border-slate-100"
                     @click="openLightbox('{{ $photo->getUrl() }}')">
                    <img src="{{ $photo->getUrl('thumb') }}" 
                         loading="lazy"
                         class="w-full h-auto object-cover transition-transform duration-700 group-hover:scale-105" 
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

        @if($hasMore)
        <div class="text-center pt-8">
            <button wire:click="loadMore" 
                    wire:loading.attr="disabled"
                    class="group relative px-12 py-5 bg-sky-600 text-white rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-sky-700 transition-all overflow-hidden">
                <div class="absolute inset-0 w-1/4 h-full bg-white/20 -skew-x-12 -translate-x-full group-hover:animate-shine"></div>
                <span wire:loading.remove>Cargar más momentos</span>
                <span wire:loading>Optimizando carga...</span>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- VIDEOS SECTION --}}
    @if($videos->count() > 0)
    <div class="mt-28 space-y-12">
        <div class="text-center">
            <h3 class="text-3xl font-black text-slate-800 uppercase">Revive la <span class="text-sky-500">Acción</span></h3>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] mt-2">Cobertura cinematográfica</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @foreach($videos as $video)
                <div class="rounded-[3rem] overflow-hidden bg-slate-900 shadow-2xl h-80 relative group border-4 border-white">
                    <video class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" controls>
                        <source src="{{ $video->getUrl() }}">
                    </video>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- LIGHTBOX OVERLAY --}}
    <div x-show="selectedPhoto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 md:p-10"
         @click.self="closeLightbox()"
         style="display: none;">
        
        <button @click="closeLightbox()" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors z-[110]">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <img :src="selectedPhoto" 
             class="max-w-full max-h-full rounded-2xl md:rounded-[2rem] shadow-2xl object-contain border-4 border-white/10">
    </div>

    <style>
        @keyframes shine {
            from { transform: translateX(-100%) skewX(-12deg); }
            to { transform: translateX(400%) skewX(-12deg); }
        }
        .animate-shine {
            animation: shine 1.5s infinite;
        }
    </style>
</div>
