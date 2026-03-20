@extends('layouts.admin')

@section('title', 'Participantes')
@section('header_title', 'Competidores Registrados')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Directorio de <span class="text-emerald-500">Participantes</span></h1>
            <p class="text-slate-500 font-medium font-bold italic italic">Gestione los competidores de todos los eventos activos.</p>
        </div>
        <a href="{{ route('admin.participants.create') }}" class="inline-flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-emerald-400/20">
            Nuevo Competidor
        </a>
    </div>


    <div class="dashboard-card overflow-hidden italic italic font-medium">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Nombre</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Evento / Cat</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Estado</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($participants as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs uppercase italic italic">
                                        {{ substr($p->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 uppercase italic italic">{{ $p->name }}</div>
                                        <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $p->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs font-bold text-slate-700 uppercase italic italic truncate max-w-[150px]">{{ $p->event->name }}</div>
                                <div class="text-[10px] text-slate-400 font-medium italic italic">{{ $p->category }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div x-data="{ active: {{ $p->is_active ? 'true' : 'false' }} }">
                                    <button @click="fetch('{{ route('admin.participants.toggle', $p) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => active = d.is_active)"
                                            class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                                            :class="active ? 'bg-emerald-500' : 'bg-slate-200'">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                              :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 text-[10px] font-black uppercase italic italic tracking-tight">
                                    <a href="{{ route('admin.participants.edit', $p) }}" class="text-sky-500 hover:underline">Gestionar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-slate-400 font-bold italic italic">No hay participantes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
