@extends('layouts.admin')

@section('title', 'Gestión de Eventos')
@section('header_title', 'Listado de Eventos')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Eventos <span class="text-sky-500">Configurados</span></h1>
            <p class="text-slate-500 font-medium font-bold">Total de eventos registrados en la plataforma: {{ $events->count() }}</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="inline-flex items-center justify-center gap-3 bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-slate-900/10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear Evento
        </a>
    </div>


    <div class="dashboard-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Evento</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Fecha / Slug</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Publicidad</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Activo</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic italic font-medium">
                    @foreach($events as $event)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                                        @if($event->logo_path)
                                            <img src="{{ Storage::url($event->logo_path) }}" class="w-full h-full object-contain">
                                        @else
                                            <span class="text-slate-300 font-black text-xs uppercase italic italic">PRO</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 uppercase italic italic truncate max-w-[200px]">{{ $event->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Ref: {{ $event->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-bold text-slate-700 italic italic">{{ $event->date->format('d M, Y') }}</div>
                                <div class="text-[10px] text-sky-500 font-medium truncate max-w-[150px]">/eventos/{{ $event->slug }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex gap-2">
                                    @if($event->getSetting('enable_public_vote') === 'true')
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-black uppercase tracking-tighter border border-blue-100">Voto</span>
                                    @endif
                                    @if($event->getSetting('enable_social_points') === 'true')
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[9px] font-black uppercase tracking-tighter border border-indigo-100">Social</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div x-data="{ active: {{ $event->is_active ? 'true' : 'false' }}, loading: false }">
                                    <button @click="loading = true; fetch('{{ route('admin.events.toggle', $event) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
                                            .then(r => r.json()).then(d => {active = d.is_active; loading = false})"
                                            :disabled="loading"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="active ? 'bg-sky-500' : 'bg-slate-200'">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                              :class="active ? 'translate-x-5' : 'translate-x-0'">
                                            <svg x-show="loading" class="animate-spin h-3 w-3 text-sky-500 m-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.tarima.control', $event) }}" class="text-slate-400 hover:text-sky-500 transition-colors" title="Control de Tarima (Modo Ceremonia)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.ballots.print', $event) }}" class="text-slate-400 hover:text-indigo-500 transition-colors" title="Imprimir Papeletas PDF" target="_blank">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.events.categories.index', $event) }}" class="text-slate-400 hover:text-sky-500 transition-colors" title="Gestionar Rúbricas">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="text-slate-400 hover:text-sky-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Seguro de eliminar este evento? No se podrá si ya tiene puntajes.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-slate-400 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
@endsection
