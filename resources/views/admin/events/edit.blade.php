@extends('layouts.admin')

@section('title', 'Editar Evento')
@section('header_title', 'Ajustes del Evento')

@section('content')
    <div class="mb-12">
        <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Editar <span class="text-sky-500">Evento</span></h1>
        <p class="text-slate-500 font-medium font-bold italic italic">Actualice la información y configuración de la competencia.</p>
    </div>

    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-4xl mx-auto italic italic font-medium">
        @csrf
        @method('PUT')
        
        <div class="dashboard-card p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre del Evento</label>
                    <input type="text" name="name" required value="{{ $event->name }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Fecha y Hora</label>
                    <input type="datetime-local" name="date" required value="{{ $event->date->format('Y-m-d\TH:i') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Subtítulo Sección "Sobre el Evento"</label>
                    <input type="text" name="about_subtitle" value="{{ old('about_subtitle', $event->getSetting('about_subtitle', 'Sobre el Evento')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Título Sección "Sobre el Evento"</label>
                    <input type="text" name="about_title" value="{{ old('about_title', $event->getSetting('about_title', 'Una competencia familiar y emocionante')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Descripción Público (Contenido de la Sección)</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all" placeholder="Describa el evento para los visitantes...">{{ $event->description }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Cronograma (Opcional)</label>
                <textarea name="event_schedule" rows="4" placeholder="Ej: 13:00 Apertura, 14:00 Inicio..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">{{ $event->getSetting('event_schedule') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Cambiar Logo</label>
                    <input type="file" name="logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition-colors">
                    @if($event->logo_path)
                        <div class="mt-2 text-[10px] font-bold text-sky-500 uppercase italic">Actualmente hay un logo guardado</div>
                    @endif
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Cambiar Banner (Hero)</label>
                    <input type="file" name="banner" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>
            </div>
        </div>

        <!-- Toggles / Settings -->
        <div class="dashboard-card p-10 space-y-8 italic italic font-medium">
            <h2 class="text-xl font-bold text-slate-900 mb-6 uppercase tracking-tight">Preferencias de Interacción</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Evento Activo</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Habilitar operaciones del evento</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $event->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Publicar en Web</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Visible para visitantes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ $event->is_published ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Voto Público</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Habilitar botones de voto</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_public_vote" value="1" class="sr-only peer" {{ $event->getSetting('enable_public_vote') === 'true' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Puntos Sociales</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Sumar puntos de redes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_social_points" value="1" class="sr-only peer" {{ $event->getSetting('enable_social_points') === 'true' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Live Leaderboard</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Visible para participantes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_leaderboard_to_participants" value="1" class="sr-only peer" {{ $event->getSetting('show_leaderboard_to_participants') === 'true' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10">
            <a href="{{ route('admin.events.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition">Cancelar</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-sky-400/20">
                Actualizar Evento
            </button>
        </div>
    </form>
@endsection
