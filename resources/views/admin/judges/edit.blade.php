@extends('layouts.admin')

@section('title', 'Editar Juez')
@section('header_title', 'Perfil del Jurado')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Editar <span class="text-indigo-500">Juez</span></h1>
            <p class="text-slate-500 font-medium italic italic">Actualice los datos del jurado y sus eventos asignados.</p>
        </div>
        <a href="{{ route('admin.judges.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">← Volver</a>
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

    <form action="{{ route('admin.judges.update', $judge) }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-4xl mx-auto">
        @csrf
        @method('PUT')

        {{-- Personal Info --}}
        <div class="dashboard-card p-10 space-y-8">
            <h2 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-100 pb-4">Información Personal</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre Completo</label>
                    <input type="text" name="name" required value="{{ old('name', $judge->name) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Email de Acceso</label>
                    <input type="email" name="email" required value="{{ old('email', $judge->email) }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nueva Contraseña <span class="normal-case text-slate-300">(dejar vacío para no cambiar)</span></label>
                    <input type="password" name="password"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Especialidad / Áreas</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}" placeholder="Ej: Parrilla, Técnica, Presentación"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Biografía / Trayectoria</label>
                <textarea name="bio" rows="4"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">{{ old('bio', $judge->bio) }}</textarea>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Cambiar Foto de Perfil</label>
                <input type="file" name="photo"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                @if($judge->photo_path)
                    <div class="flex items-center gap-4 mt-2">
                        <img src="{{ $judge->photo_path }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-100">
                        <span class="text-[10px] font-bold text-indigo-500 uppercase italic italic">Foto actual guardada</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status --}}
        <div class="dashboard-card p-10">
            <h2 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-100 pb-4 mb-8">Estado del Juez</h2>
            <div class="flex items-center justify-between p-6 rounded-[2rem] border border-slate-100 bg-slate-50/50">
                <div>
                    <div class="text-sm font-black text-slate-800 uppercase italic italic">Juez Activo</div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">Puede iniciar sesión y calificar</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $judge->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        {{-- Event Assignment --}}
        <div class="dashboard-card p-10">
            <h2 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-100 pb-4 mb-8">Eventos Asignados</h2>
            @if($events->isEmpty())
                <p class="text-slate-400 italic italic font-medium text-sm">No hay eventos creados aún.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($events as $event)
                        <label class="flex items-center gap-4 p-5 rounded-2xl border cursor-pointer transition-all
                                      {{ in_array($event->id, $assignedEvents) ? 'border-indigo-200 bg-indigo-50' : 'border-slate-100 bg-slate-50/50 hover:border-indigo-100' }}">
                            <input type="checkbox" name="events[]" value="{{ $event->id }}"
                                   class="w-4 h-4 rounded accent-indigo-600"
                                   {{ in_array($event->id, $assignedEvents) ? 'checked' : '' }}>
                            <div>
                                <span class="text-sm font-black text-slate-800 uppercase italic italic block">{{ $event->name }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $event->date->format('d M Y') }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-6 pt-4">
            <a href="{{ route('admin.judges.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">Cancelar</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-400/20 italic italic">
                Guardar Cambios
            </button>
        </div>
    </form>
@endsection
