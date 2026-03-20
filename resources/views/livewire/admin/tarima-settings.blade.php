<div class="p-8 max-w-4xl mx-auto space-y-12">
    <div>
        <a href="{{ route('admin.tarima.control', $event) }}" class="text-sky-500 text-xs font-black uppercase tracking-widest hover:underline flex items-center gap-2 mb-4">
            ← Volver al Control
        </a>
        <h1 class="text-4xl font-black text-slate-900 uppercase italic">Ajustes <span class="text-sky-500">Visuales</span></h1>
        <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest mt-2">Configura el estilo de la pantalla gigante</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        <!-- Background Selection -->
        <div class="dashboard-card p-10 space-y-8">
            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Ambiente (Fondo)</h2>
            
            <div class="space-y-4">
                <label class="block text-sm font-black text-slate-700 uppercase italic">ID/URL de Video (YouTube Loop)</label>
                <div class="flex items-center gap-4">
                    <input type="text" wire:model="video_url" placeholder="Ej: yb-U_2m1zE" class="flex-1 bg-slate-50 border-none rounded-2xl px-6 py-4 font-mono font-bold text-slate-600">
                </div>
                <p class="text-[9px] text-slate-400">Si hay video, se mostrará en bucle detrás de todo.</p>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-black text-slate-700 uppercase italic">Imagen de Fondo (Respaldo)</label>
                <div class="space-y-4">
                    @if($current_background_image)
                        <img src="{{ $current_background_image }}" class="w-full h-32 object-cover rounded-2xl border border-slate-100">
                    @endif
                    <div class="relative w-full">
                        <input type="file" wire:model="new_background_image" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center">
                            <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Haz clic para subir imagen</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-black text-slate-700 uppercase italic">Color de Fondo Fallback</label>
                <div class="flex items-center gap-4">
                    <input type="color" wire:model="bg_color" class="w-20 h-20 rounded-2xl cursor-pointer border-4 border-slate-100">
                    <input type="text" wire:model="bg_color" class="flex-1 bg-slate-50 border-none rounded-2xl px-6 py-4 font-mono font-bold text-slate-600">
                </div>
            </div>
        </div>

        <!-- Animation & FX Selection -->
        <div class="dashboard-card p-10 space-y-8">
            <h2 class="text-xs font-black uppercase tracking-widest text-slate-400">Estilo y Animación</h2>

            <div class="space-y-4">
                <label class="block text-sm font-black text-slate-700 uppercase italic">Color de Acento (Glow)</label>
                <div class="flex items-center gap-4">
                    <input type="color" wire:model="accent_color" class="w-20 h-20 rounded-2xl cursor-pointer border-4 border-slate-100">
                    <input type="text" wire:model="accent_color" class="flex-1 bg-slate-50 border-none rounded-2xl px-6 py-4 font-mono font-bold text-slate-600">
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-black text-slate-700 uppercase italic">Tipo de Revelación</label>
                <select wire:model="reveal_animation" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-slate-600 focus:ring-2 focus:ring-sky-500">
                    <option value="flip">3D Flip (Elegante)</option>
                    <option value="scale">Escalamiento (Impactante)</option>
                </select>
            </div>

            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-[2rem]">
                <div>
                    <span class="block text-sm font-black text-slate-900 uppercase italic">Confeti Digital</span>
                    <span class="text-[10px] text-slate-500 font-bold">Explosión al revelar ganadores</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="enable_confetti" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                </label>
            </div>
        </div>

    </div>

    <div class="flex justify-end pt-8">
        <button wire:click="save" wire:loading.attr="disabled" class="bg-slate-900 text-white px-12 py-5 rounded-2xl font-black uppercase text-xs tracking-widest shadow-2xl hover:bg-sky-500 transition-all transform active:scale-95 flex items-center gap-4">
            <span wire:loading.remove>Guardar Configuración</span>
            <span wire:loading>Guardando...</span>
            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </button>
    </div>
</div>
