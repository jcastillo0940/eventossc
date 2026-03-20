@extends('layouts.app-dashboard')

@section('title', 'Panel de Juez')
@section('role_name', 'Juez')
@section('header_title', 'Sesión de Calificación')

@section('content')
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2 uppercase italic italic">Panel de <span class="text-indigo-500">Evaluación</span></h1>
        <p class="text-slate-500 font-medium font-bold">Seleccione un evento para comenzar a calificar a los participantes.</p>
    </div>

    @if($events->isEmpty())
        <div class="dashboard-card p-20 text-center flex flex-col items-center gap-6 border-dashed border-2">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                 <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-slate-500 italic italic font-medium text-lg">No tienes eventos asignados activos en este momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <div class="dashboard-card p-8 flex flex-col h-full hover:border-indigo-500 transition-all cursor-pointer">
                    <div class="flex items-center gap-4 mb-8">
                        @if($event->logo_path)
                            <img src="{{ Storage::url($event->logo_path) }}" class="w-14 h-14 object-contain">
                        @else
                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 font-black italic italic">PRO</div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 uppercase italic italic tracking-tight mb-1">{{ $event->name }}</h3>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $event->date->format('d M, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4 mb-10 flex-grow">
                        <div class="flex items-center justify-between text-sm py-3 border-b border-slate-50">
                            <span class="text-slate-500 font-medium italic italic">Participantes por evaluar</span>
                            <span class="font-black text-slate-800">{{ $event->participants->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm py-3 border-b border-slate-50">
                            <span class="text-slate-500 font-medium italic italic">Categorías asignadas</span>
                            <span class="font-black text-slate-800">{{ $event->evaluationCategories->count() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('judge.evaluate', $event) }}" class="w-full flex items-center justify-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white h-14 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-500/10">
                        Entrar a Evaluar
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
