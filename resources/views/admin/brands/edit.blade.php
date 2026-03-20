@extends('layouts.admin')

@section('title', 'Editar Marca')
@section('header_title', 'Editar Patrocinador')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Editar <span class="text-indigo-500">Marca</span></h1>
            <p class="text-slate-500 font-medium italic italic">Actualice los datos del patrocinador.</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">← Volver</a>
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

    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-2xl mx-auto">
        @csrf
        @method('PUT')

        <div class="dashboard-card p-10 space-y-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre de la Marca</label>
                <input type="text" name="name" required value="{{ old('name', $brand->name) }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Evento Asociado</label>
                <select name="event_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $brand->event_id) == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Orden de Aparición</label>
                    <input type="number" name="order" value="{{ old('order', $brand->order) }}" min="0"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Cambiar Logo</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    @if($brand->logo_path)
                        <div class="text-[10px] font-bold text-indigo-500 uppercase italic italic">Logo actual guardado</div>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between p-6 rounded-2xl bg-slate-50/50 border border-slate-100">
                <span class="text-sm font-black text-slate-800 uppercase italic italic">Marca Activa</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $brand->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6">
            <a href="{{ route('admin.brands.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">Cancelar</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-400/20 italic italic">
                Guardar Cambios
            </button>
        </div>
    </form>
@endsection
