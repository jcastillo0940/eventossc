<div class="p-8 max-w-6xl mx-auto space-y-12">
    <div>
        <h1 class="text-4xl font-black text-slate-900 uppercase italic">Ceremonia en <span class="text-sky-500">Tarima</span></h1>
        <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest mt-2">Selecciona un evento para abrir el control de visualización</p>
    </div>

    @if($events->isEmpty())
        <div class="dashboard-card p-20 text-center space-y-6">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 01-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="text-slate-400 font-bold italic italic uppercase">No hay eventos activos publicados en este momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="dashboard-card group hover:border-sky-500 transition-all duration-300 overflow-hidden flex flex-col">
                    <div class="h-32 bg-slate-900 relative">
                        @if($event->banner_path)
                            <img src="{{ Storage::url($event->banner_path) }}" class="w-full h-full object-cover opacity-50 group-hover:opacity-100 transition-opacity">
                        @endif
                        <div class="absolute inset-x-8 -bottom-6 flex items-end">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-lg p-2 border border-slate-100">
                                @if($event->logo_path)
                                    <img src="{{ Storage::url($event->logo_path) }}" class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full bg-sky-500 flex items-center justify-center text-white text-[8px] font-black">PRO</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 pt-10 flex-grow space-y-6">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 uppercase italic truncate tracking-tighter">{{ $event->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $event->getFormattedDate() }}</p>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('admin.tarima.control', $event) }}" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-center font-black text-xs uppercase tracking-widest hover:bg-sky-500 transition-all transform active:scale-95 shadow-xl">
                                Abrir Control de Tarima
                            </a>
                            <a href="{{ route('events.stage', $event->slug) }}" target="_blank" class="w-full py-4 bg-sky-50 text-sky-600 rounded-2xl text-center font-black text-xs uppercase tracking-widest border border-sky-100 hover:bg-sky-600 hover:text-white transition-all">
                                Pantalla Gigante (KDS)
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
