@extends('layouts.admin')

@section('title', 'Crear Evento')
@section('header_title', 'Configuración de Nuevo Evento')

@section('content')
    <div class="mb-12">
        <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Nuevo <span class="text-sky-500">Evento</span></h1>
        <p class="text-slate-500 font-medium font-bold">Configure los parámetros iniciales de la competencia.</p>
    </div>

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-4xl mx-auto">
        @csrf
        
        <div class="dashboard-card p-10 space-y-8">
            <div class="grid grid-cols-1 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre del Evento</label>
                    <input type="text" name="name" required placeholder="Ej: BBQ Challenge Panamá" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all" value="{{ old('name') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Fecha y Hora (Si se conoce)</label>
                    <input type="datetime-local" name="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all" value="{{ old('date') }}">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Formato de Fecha</label>
                    <select name="date_display_mode" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                        <option value="full">Día/Mes/Año + Hora</option>
                        <option value="month_year">Solo Mes y Año</option>
                        <option value="tba">Próximamente (TBD)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Subtítulo Sección "Sobre el Evento"</label>
                    <input type="text" name="about_subtitle" placeholder="Ej: ● Sobre el Evento" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all" value="{{ old('about_subtitle', 'Sobre el Evento') }}">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Título Sección "Sobre el Evento"</label>
                    <input type="text" name="about_title" placeholder="Ej: Una competencia familiar y emocionante" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all" value="{{ old('about_title', 'Una competencia familiar y emocionante') }}">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Descripción Público (Contenido de la Sección)</label>
                <textarea name="description" rows="4" placeholder="Describa el evento para los visitantes..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Logo del Evento (PNG/JPG)</label>
                    <input type="file" name="logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100">
                </div>
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Banner Principal (Hero)</label>
                    <input type="file" name="banner" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
        </div>

        <!-- Toggles / Settings -->
        <div class="dashboard-card p-10 space-y-8 italic italic font-medium">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Ajustes de Interacción y Visibilidad</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Evento Activo</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Habilitar operaciones</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Publicar Evento</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Visible en el portal público</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Activar Voto Público</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Permite votar a visitantes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_public_vote" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Puntos Sociales</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Calificación extra por redes</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_social_points" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">Leaderboard Participante</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Visible para competidores</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="show_leaderboard_to_participants" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10">
            <a href="{{ route('admin.events.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition">Cancelar</a>
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-sky-400/20">
                Guardar Evento
            </button>
        </div>
    </form>
@endsection
