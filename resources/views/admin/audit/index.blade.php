@extends('layouts.admin')

@section('title', 'Auditoría de Puntajes')
@section('header_title', 'Módulo de Supervisión')

@section('content')
    <div class="mb-12">
        <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Auditoría de <span class="text-sky-500">Puntajes</span></h1>
        <p class="text-slate-500 font-medium font-bold italic italic">Supervise y corrija calificaciones registradas por el jurado o digitadores.</p>
    </div>

    <!-- Filter Bar -->
    <div class="dashboard-card p-8 mb-12 italic italic font-medium">
        <form action="{{ route('admin.audit.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Filtrar por Evento</label>
                <select name="event_id" @change="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none transition-all">
                    <option value="">Todos los Eventos</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Filtrar por Categoría</label>
                <select name="category_id" @change="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none transition-all">
                    <option value="">Todas las Categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Por Participante</label>
                <select name="participant_id" @change="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none transition-all">
                    <option value="">Todos los Participantes</option>
                    @foreach($participants as $participant)
                        <option value="{{ $participant->id }}" {{ request('participant_id') == $participant->id ? 'selected' : '' }}>{{ $participant->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-slate-900 border-2 border-slate-800 hover:bg-sky-500 hover:text-white text-white h-14 rounded-xl font-black text-xs uppercase tracking-widest font-black transition italic italic font-medium">
                Aplicar Filtros
            </button>
        </form>
    </div>


    <div class="dashboard-card overflow-hidden italic italic font-medium">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Participante</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Juez</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Criterio (Cat)</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Puntaje</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Acción Emergencia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($scores as $score)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-900 uppercase italic italic truncate max-w-[150px]">{{ $score->participant->name }}</div>
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $score->event->name }}</div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[9px] font-black uppercase italic italic border border-indigo-100">
                                {{ $score->judge->name }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="text-xs font-bold text-slate-700 uppercase italic italic">{{ $score->criterion->name }}</div>
                            <div class="text-[9px] text-slate-400 font-medium italic italic">({{ $score->criterion->category->name }})</div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-lg font-black text-sky-500 italic italic">{{ number_format($score->score, 1) }}</span>
                            <span class="text-[8px] text-slate-300 italic">/ {{ $score->criterion->max_score }}</span>
                        </td>
                        <td class="px-8 py-6 text-right" x-data="{ open: false, newScore: {{ $score->score }} }">
                            <button @click="open = !open" class="text-indigo-400 hover:text-indigo-600 font-black text-[10px] uppercase italic italic tracking-widest transition">Corregir</button>
                            
                            <!-- Inline Edit Overlay -->
                            <div x-show="open" x-cloak class="absolute right-8 mt-2 w-72 bg-white border border-slate-200 rounded-2xl shadow-2xl p-6 z-50 text-left">
                                <h4 class="text-xs font-black uppercase mb-4 text-slate-800">Corrección de Emergencia</h4>
                                <form action="{{ route('admin.audit.update', $score) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Nuevo Puntaje</label>
                                        <input type="number" step="0.1" name="score" required x-model="newScore" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all font-black">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Motivo del Cambio</label>
                                        <textarea name="reason" required rows="2" placeholder="Ej: Error de digitación" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium"></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" @click="open = false" class="flex-grow py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-[10px] uppercase transition italic italic">Cancelar</button>
                                        <button type="submit" class="flex-grow py-2 bg-slate-900 border-2 border-slate-800 text-white rounded-xl font-black text-[10px] uppercase transition italic italic">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-slate-400 italic italic font-medium italic font-bold">No se encontraron puntajes bajo estos filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-8">
        {{ $scores->appends(request()->all())->links() }}
    </div>
@endsection
