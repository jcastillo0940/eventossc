@extends('layouts.admin')

@section('title', 'Nuevo Juez')
@section('header_title', 'Registro Jurado')

@section('content')
    <div class="mb-12">
        <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Registrar <span class="text-indigo-500">Juez</span></h1>
        <p class="text-slate-500 font-medium font-bold">Configure el perfil técnico y asigne eventos al jurado.</p>
    </div>

    <form action="{{ route('admin.judges.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-4xl mx-auto">
        @csrf
        
        <div class="dashboard-card p-10 space-y-8 italic italic font-medium">
            <h2 class="text-xl font-black text-slate-900 mb-6 uppercase tracking-tight italic italic">Datos de Acceso y Perfil</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: Chef Antonio Sánchez" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Coreo Electrónico</label>
                    <input type="email" name="email" required placeholder="antonio@chef.com" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Contraseña Temporal</label>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Especialidad (Ej: Grill Master)</label>
                    <input type="text" name="specialty" placeholder="Experto en Costillas" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Biografía Corta</label>
                <textarea name="bio" rows="4" placeholder="Describa la trayectoria del juez para el público..." class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Fotografía de Perfil (Avatar)</label>
                <input type="file" name="photo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
            </div>
        </div>

        <!-- Event Assignment -->
        <div class="dashboard-card p-10 space-y-8 italic italic font-medium">
            <h2 class="text-xl font-bold text-slate-900 mb-6 uppercase tracking-tight italic italic">Asignación de <span class="text-indigo-500 font-black italic italic">Eventos</span></h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <label class="relative flex flex-col p-6 rounded-[1.5rem] border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-indigo-100 transition-all cursor-pointer group">
                        <input type="checkbox" name="events[]" value="{{ $event->id }}" class="absolute top-4 right-4 w-5 h-5 accent-indigo-500 text-indigo-600 rounded-xl border-slate-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-500 font-black text-[10px] italic italic">#{{ $loop->iteration }}</div>
                            <span class="text-sm font-black text-slate-800 uppercase italic italic truncate max-w-[150px]">{{ $event->name }}</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $event->date->format('d M, Y') }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-6 pt-10">
            <a href="{{ route('admin.judges.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic font-medium">Cancelar</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-600/20 italic italic font-medium">
                Guardar Juez
            </button>
        </div>
    </form>
@endsection
