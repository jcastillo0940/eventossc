@extends('layouts.admin')

@section('title', 'Nuevo Competidor')
@section('header_title', 'Registrar Participante')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Registrar <span class="text-emerald-500">Competidor</span></h1>
            <p class="text-slate-500 font-medium italic italic">Vincule al participante con un evento y categoría.</p>
        </div>
        <a href="{{ route('admin.participants.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">← Volver</a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-8 font-bold italic italic text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.participants.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-2xl mx-auto">
        @csrf

        <div class="dashboard-card p-10 space-y-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre del Competidor</label>
                <input type="text" name="name" required placeholder="Ej: Carlos Rodríguez" value="{{ old('name') }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Email de Acceso</label>
                <input type="email" name="email" required placeholder="carlos@email.com" value="{{ old('email') }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all italic italic font-medium">
                <p class="text-[10px] text-slate-400 font-medium italic italic">Se creará un usuario con contraseña <strong>password</strong> por defecto.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Evento</label>
                    <select name="event_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all italic italic font-medium">
                        <option value="">— Seleccione Evento —</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Categoría / Mesa</label>
                    <input type="text" name="category" required placeholder="Ej: Cerdo, Pollo, General" value="{{ old('category') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all italic italic font-medium">
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Foto del Competidor</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-colors">
            </div>

            <div class="flex items-center justify-between p-6 rounded-2xl bg-slate-50/50 border border-slate-100">
                <span class="text-sm font-black text-slate-800 uppercase italic italic">Participante Activo</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6">
            <a href="{{ route('admin.participants.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">Cancelar</a>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-emerald-400/20 italic italic">
                Registrar Competidor
            </button>
        </div>
    </form>
@endsection
