<div class="p-8 max-w-6xl mx-auto space-y-12">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-slate-900 uppercase italic">Galería de <span class="text-sky-500">Eventos</span></h1>
            <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest mt-2">{{ $event->name }}</p>
        </div>
        <div class="relative group">
            <input type="file" multiple wire:model="uploads" class="absolute inset-0 opacity-0 cursor-pointer z-10">
            <button class="bg-sky-500 text-white px-10 py-5 rounded-3xl font-black text-xs uppercase tracking-widest shadow-2xl hover:bg-slate-900 transition-all flex items-center gap-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Subir Fotos / Videos
            </button>
        </div>
    </div>

    <!-- PHOTOS -->
    <div class="space-y-6">
        <h2 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] px-2 flex items-center gap-3">
            <span class="w-10 h-[1px] bg-slate-200"></span>
            Fotografías del Evento
            <span class="flex-grow h-[1px] bg-slate-200"></span>
        </h2>
        @if($photos->isEmpty())
            <div class="p-20 text-center border-4 border-dashed border-slate-50 rounded-[3rem]">
                <p class="text-slate-300 font-black italic italic uppercase">No hay fotos registradas.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($photos as $photo)
                    <div class="dashboard-card relative group overflow-hidden h-48 rounded-[2rem] border-4 {{ $photo->getCustomProperty('highlight') ? 'border-sky-500 shadow-2xl' : 'border-slate-50' }}">
                        <img src="{{ $photo->getUrl() }}" class="w-full h-full object-cover">
                        
                        {{-- Tag de destacado --}}
                        @if($photo->getCustomProperty('highlight'))
                            <div class="absolute top-4 left-4 z-20 bg-sky-500 text-white p-2 rounded-xl shadow-lg animate-bounce">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            {{-- Botón de Destacar --}}
                            <button wire:click="toggleHighlight({{ $photo->id }})" 
                                    class="p-3 {{ $photo->getCustomProperty('highlight') ? 'bg-amber-500' : 'bg-white/20' }} text-white rounded-xl shadow-xl hover:scale-110 transition-all backdrop-blur-md">
                                <svg class="w-6 h-6" fill="{{ $photo->getCustomProperty('highlight') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </button>

                            <button wire:click="deleteMedia({{ $photo->id }})" class="p-3 bg-red-600 text-white rounded-xl shadow-xl hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- VIDEOS -->
    <div class="space-y-6">
        <h2 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] px-2 flex items-center gap-3">
            <span class="w-10 h-[1px] bg-slate-200"></span>
            Videos Cinematográficos
            <span class="flex-grow h-[1px] bg-slate-200"></span>
        </h2>
        @if($videos->isEmpty())
            <div class="p-20 text-center border-4 border-dashed border-slate-50 rounded-[3rem]">
                <p class="text-slate-300 font-black italic italic uppercase">No hay videos registrados.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($videos as $video)
                    <div class="dashboard-card relative group overflow-hidden rounded-[2.5rem] bg-slate-900 h-64">
                        <video class="w-full h-full object-cover opacity-50" controls>
                            <source src="{{ $video->getUrl() }}">
                        </video>
                        <div class="absolute top-4 right-4 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="deleteMedia({{ $video->id }})" class="p-3 bg-red-600 text-white rounded-xl shadow-xl hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
