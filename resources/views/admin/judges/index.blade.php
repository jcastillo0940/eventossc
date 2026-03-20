@extends('layouts.admin')

@section('title', 'Cuerpo de Jueces')
@section('header_title', 'Jurado Calificador')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Cuerpo de <span class="text-indigo-500">Jueces</span></h1>
            <p class="text-slate-500 font-medium font-bold">Gestione los perfiles de los evaluadores y sus asignaciones.</p>
        </div>
        <a href="{{ route('admin.judges.create') }}" class="inline-flex items-center justify-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-400/20">
            Registrar Juez
        </a>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($judges as $judge)
            <div class="dashboard-card p-10 flex flex-col items-center text-center relative overflow-hidden italic italic font-medium">
                <!-- Status Toggle -->
                <div x-data="{ active: {{ $judge->is_active ? 'true' : 'false' }}, loading: false }" 
                     class="absolute top-6 right-6">
                    <button @click="loading = true; fetch('{{ route('admin.judges.toggle', $judge) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
                            .then(r => r.json()).then(d => {active = d.is_active; loading = false})"
                            class="relative inline-flex h-4 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                            :class="active ? 'bg-indigo-500' : 'bg-slate-200'">
                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                              :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>

                <div class="w-24 h-24 rounded-[2rem] bg-indigo-50 border-2 border-white shadow-xl overflow-hidden mb-6 flex-shrink-0">
                    @if($judge->photo_path)
                        <img src="{{ Storage::url($judge->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-indigo-400 font-black italic italic">
                             {{ substr($judge->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <h3 class="text-lg font-black text-slate-900 uppercase italic italic tracking-tight mb-1">{{ $judge->name }}</h3>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">Experto {{ $judge->judgeEvents->first()?->pivot->specialty ?? 'General' }}</span>
                
                <div class="flex flex-wrap justify-center gap-1.5 mb-8">
                    @forelse($judge->judgeEvents as $event)
                        <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded text-[8px] font-black uppercase tracking-widest border border-slate-100 truncate max-w-[80px]">
                            {{ $event->name }}
                        </span>
                    @empty
                        <span class="text-[8px] text-slate-300 uppercase italic">Sin eventos asignados</span>
                    @endforelse
                </div>

                <div class="w-full h-[1px] bg-slate-100 mb-6"></div>

                <div class="flex items-center gap-4 w-full justify-between">
                    <a href="{{ route('admin.judges.edit', $judge) }}" class="flex-grow bg-slate-50 hover:bg-slate-100 text-slate-600 px-4 py-3 rounded-xl font-bold text-xs transition border border-slate-100">
                        Editar Perfil
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
