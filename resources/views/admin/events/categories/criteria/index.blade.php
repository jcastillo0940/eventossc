@extends('layouts.admin')

@section('title', 'Criterios de Evaluación')
@section('header_title', 'Rúbrica: ' . $category->name)

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Criterios de <span class="text-sky-500">{{ $category->name }}</span></h1>
            <p class="text-slate-500 font-medium font-bold italic italic">Configure los pesos y puntajes máximos por criterio.</p>
        </div>
        <a href="{{ route('admin.events.categories.index', $event) }}" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition italic italic font-medium underline">Volver a Categorías</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 font-bold italic italic">
             {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 italic italic font-medium">
        <!-- New Criterion Form -->
        <div class="lg:col-span-1">
            <div class="dashboard-card p-10">
                <h3 class="text-lg font-black text-slate-800 uppercase italic italic mb-8 border-b-2 border-sky-500 pb-2 inline-block">Añadir Criterio</h3>
                <form action="{{ route('admin.events.categories.criteria.store', [$event, $category]) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre del Criterio</label>
                        <input type="text" name="name" required placeholder="Ej: Cocción, Sabor..." class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Puntaje Máximo</label>
                            <input type="number" step="0.1" name="max_score" required value="10" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Peso (%)</label>
                            <input type="number" step="0.01" name="weight" required value="1" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 border-2 border-slate-800 hover:bg-sky-500 hover:text-white text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-slate-900/10 italic italic">
                        Confirmar Criterio
                    </button>
                </form>
            </div>
        </div>

        <!-- Criteria List -->
        <div class="lg:col-span-2 space-y-6 italic italic font-medium">
            <div class="dashboard-card overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Criterio</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Puntaje Máximo</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Peso</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($criteria as $criterion)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 font-black text-slate-900 uppercase italic italic">{{ $criterion->name }}</td>
                                <td class="px-8 py-6 text-center font-bold text-sky-500">{{ number_format($criterion->max_score, 1) }} pts</td>
                                <td class="px-8 py-6 text-center font-bold text-indigo-500">x{{ $criterion->weight }}</td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <div x-data="{ active: {{ $criterion->is_active ? 'true' : 'false' }} }">
                                            <button @click="fetch('{{ route('admin.events.categories.criteria.toggle', [$event, $category, $criterion]) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => active = d.is_active)"
                                                    class="relative inline-flex h-4 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                                                    :class="active ? 'bg-indigo-500' : 'bg-slate-200'">
                                                <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                                      :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.events.categories.criteria.destroy', [$event, $category, $criterion]) }}" method="POST" onsubmit="return confirm('¿Eliminar criterio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-400 hover:text-red-600 transition-colors">
                                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
