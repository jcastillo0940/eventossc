@extends('layouts.admin')

@section('title', 'Nueva Marca / Patrocinador')
@section('header_title', 'Registrar Patrocinador')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Nueva <span class="text-indigo-500">Marca</span></h1>
            <p class="text-slate-500 font-medium italic italic text-sm">Registre un nuevo patrocinador y vincúlelo a uno o más eventos.</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition italic italic">← Volver</a>
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

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10 max-w-2xl mx-auto">
        @csrf

        <div class="dashboard-card p-10 space-y-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre de la Marca</label>
                <input type="text" name="name" required placeholder="Ej: Weber Grill" value="{{ old('name') }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Eventos Patrocinados (Selección Múltiple)</label>
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    @foreach($events as $event)
                        <label class="flex items-center gap-3 p-3 bg-white border border-slate-100 rounded-xl cursor-pointer hover:border-indigo-500 transition-all">
                            <input type="checkbox" name="event_ids[]" value="{{ $event->id }}" 
                                   class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500">
                            <span class="text-xs font-bold text-slate-700 uppercase italic italic">{{ $event->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Orden de Aparición</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all italic italic font-medium">
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Logo de la Marca</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                </div>
            </div>

            <div class="flex items-center justify-between p-6 rounded-2xl bg-slate-50/50 border border-slate-100">
                <span class="text-sm font-black text-slate-800 uppercase italic italic">Marca Activa</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6">
            <a href="{{ route('admin.brands.index') }}" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition italic italic font-bold">Cancelar</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-400/20 italic italic">
                Registrar Marca
            </button>
        </div>
    </form>
@endsection
