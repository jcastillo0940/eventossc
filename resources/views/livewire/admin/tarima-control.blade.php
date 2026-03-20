<div class="p-6 md:p-10 max-w-7xl mx-auto space-y-10">
    
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 flex flex-col lg:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-sky-50 rounded-2xl flex items-center justify-center text-sky-500 border border-sky-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-slate-900 uppercase italic tracking-tight">Control de <span class="text-sky-500">Tarima</span></h1>
                <p class="text-slate-500 font-bold uppercase text-sm tracking-widest mt-1 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    Evento: {{ $event->name }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 w-full lg:w-auto">
            <a href="{{ route('admin.tarima.settings', $event) }}" class="flex-1 lg:flex-none flex items-center justify-center gap-2 bg-white text-slate-700 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest border-2 border-slate-200 hover:border-sky-500 hover:text-sky-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Ajustes Visuales
            </a>
            <button wire:click="resetStage" onclick="confirm('¿Seguro de reiniciar la pantalla? Se ocultará todo.') || event.stopImmediatePropagation()" class="flex-1 lg:flex-none flex items-center justify-center gap-2 bg-red-50 text-red-600 px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest border-2 border-red-100 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reiniciar Pantalla
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @for($i = 3; $i >= 1; $i--)
            @php
                $isRevealed = in_array($i, $revealed);
                $iconColor = $i == 1 ? 'text-yellow-400' : ($i == 2 ? 'text-slate-400' : 'text-amber-600');
            @endphp
            
            <div class="bg-white rounded-[2rem] shadow-lg border-2 {{ $isRevealed ? 'border-sky-500 shadow-sky-500/20' : 'border-slate-100' }} p-8 flex flex-col items-center justify-between relative overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                
                @if($isRevealed)
                    <div class="absolute top-6 right-6 bg-sky-100 text-sky-600 p-2.5 rounded-full shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                @endif

                <div class="{{ $iconColor }} mb-6 drop-shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-28 h-28">
                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 27.341 27.341 0 002.056.205v.268a1.53 1.53 0 01-.366 1.004c-1.049 1.206-2.302 2.68-2.302 4.342v.942a4.526 4.526 0 00-2.013 3.52 2.25 2.25 0 00-.033.435c0 .67.554 1.218 1.225 1.25.991.047 2.05.109 3.19.167A20.573 20.573 0 0012 23.25c1.455 0 2.871-.059 4.25-.166.703-.055 1.272-.612 1.272-1.32a2.235 2.235 0 00-.033-.435 4.526 4.526 0 00-2.013-3.52v-.942c0-1.662-1.253-3.136-2.302-4.342a1.53 1.53 0 01-.366-1.004v-.268c.7-.024 1.388-.069 2.056-.205a6.753 6.753 0 006.138-5.6.75.75 0 00-.584-.859 40.589 40.589 0 00-3.071-.543V2.62a.75.75 0 00-.65-.743 41.135 41.135 0 00-9.404 0 .75.75 0 00-.65.744zm1.884 1.622a39.198 39.198 0 018.9 0v1.071c0 .247-.024.49-.071.728a25.864 25.864 0 01-4.379.791 25.864 25.864 0 01-4.38-.791 3.003 3.003 0 01-.07-.728V4.243z" clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="text-center mb-8">
                    <span class="text-sm font-black uppercase tracking-widest text-slate-400 block mb-2">Puesto #{{ $i }}</span>
                    <div class="text-6xl font-black italic text-slate-800">{{ $i }}º <span class="text-3xl text-slate-400">Lugar</span></div>
                </div>

                @if($isRevealed)
                    <div class="w-full bg-sky-50 border-2 border-sky-200 text-sky-700 h-16 rounded-2xl text-center font-black text-lg uppercase tracking-widest flex items-center justify-center gap-2">
                        <span>Revelado</span>
                    </div>
                @else
                    <button wire:click="reveal({{ $i }})" class="w-full bg-slate-900 hover:bg-sky-500 text-white h-16 rounded-2xl font-black text-lg uppercase tracking-widest transition-all shadow-xl hover:shadow-sky-500/30 active:scale-95 flex items-center justify-center gap-3 group">
                        Revelar Ahora
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </button>
                @endif
            </div>
        @endfor
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <h2 class="text-sm font-black uppercase text-white tracking-widest">Previsualización Real de Ganadores</h2>
            </div>
            <span class="bg-red-500/20 text-red-400 border border-red-500/30 px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                Sólo Visible para Ti
            </span>
        </div>
        
        <div class="divide-y divide-slate-100">
            @foreach(collect($ranking)->take(5) as $index => $row)
                <div class="px-8 py-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-6">
                        <span class="text-3xl font-black {{ $index == 0 ? 'text-yellow-400' : ($index == 1 ? 'text-slate-400' : ($index == 2 ? 'text-amber-600' : 'text-slate-200')) }} w-12 text-center">
                            {{ $index + 1 }}º
                        </span>
                        
                        <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-slate-200 flex items-center justify-center font-black text-xl text-slate-400 uppercase shadow-inner overflow-hidden">
                           @if($row['participant']->photo_path)
                               <img src="{{ Storage::url($row['participant']->photo_path) }}" class="w-full h-full object-cover">
                           @else
                               {{ substr($row['participant']->name, 0, 1) }}
                           @endif
                        </div>
                        
                        <div>
                            <div class="text-lg font-black text-slate-900 uppercase italic">{{ $row['participant']->name }}</div>
                            <div class="text-[11px] text-sky-600 uppercase font-bold tracking-widest mt-0.5">{{ $row['participant']->category }}</div>
                        </div>
                    </div>
                    <div class="text-right bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="text-2xl font-black text-slate-900 italic tracking-tighter">
                            {{ number_format($row['total_score'], 1) }} <span class="text-[10px] text-slate-400 uppercase tracking-widest ml-1 font-bold">pts</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>