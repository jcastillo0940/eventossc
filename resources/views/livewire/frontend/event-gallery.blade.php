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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($photos as $photo)
                <div class="group relative aspect-square rounded-[2.5rem] overflow-hidden bg-slate-200 shadow-xl cursor-zoom-in"
                     @click="openLightbox('{{ $photo->getUrl() }}')">
                    <img src="{{ $photo->getUrl('thumb') }}" 
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                         alt="Foto del evento">
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    </div>
                </div>
            @endforeach
        </div>

        @if($hasMore)
        <div class="text-center">
            <button wire:click="loadMore" 
                    wire:loading.attr="disabled"
                    class="px-12 py-5 bg-white text-slate-800 rounded-full font-black text-xs uppercase tracking-widest shadow-2xl hover:-translate-y-1 transition-all border border-slate-100">
                <span wire:loading.remove>Cargar más momentos</span>
                <span wire:loading>Procesando...</span>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- VIDEOS SECTION --}}
    @if($videos->count() > 0)
    <div class="mt-28 space-y-12">
        <div class="text-center">
            <h3 class="text-3xl font-black text-slate-800 uppercase italic">Revive la <span class="text-sky-500">Acción</span></h3>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] mt-2">Cobertura cinematográfica</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @foreach($videos as $video)
                <div class="rounded-[3rem] overflow-hidden bg-slate-900 shadow-2xl h-80 relative group">
                    <video class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity" controls>
                        <source src="{{ $video->getUrl() }}">
                    </video>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- LIGHTBOX OVERLAY --}}
    <template x-if="selectedPhoto">
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/95 backdrop-blur-sm p-4 md:p-10"
             @click.self="closeLightbox()">
            <button @click="closeLightbox()" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img :src="selectedPhoto" 
                 class="max-w-full max-h-full rounded-2xl md:rounded-[3rem] shadow-2xl object-contain animate-in fade-in zoom-in duration-300">
        </div>
    </template>
</div>
