@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Resumen General')

@section('content')
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2 uppercase italic italic">Bienvenido, <span class="text-sky-500">{{ Auth::user()->name }}</span></h1>
        <p class="text-slate-500 font-medium font-bold italic italic">Resumen general de las operaciones del sistema de eventos.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <div class="dashboard-card p-8 flex flex-col items-center text-center">
            <span class="text-4xl font-black text-sky-500 mb-2">{{ $stats['total_events'] }}</span>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Eventos</span>
        </div>
        <div class="dashboard-card p-8 flex flex-col items-center text-center">
            <span class="text-4xl font-black text-indigo-500 mb-2">{{ $stats['active_events'] }}</span>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Eventos Activos</span>
        </div>
        <div class="dashboard-card p-8 flex flex-col items-center text-center">
            <span class="text-4xl font-black text-emerald-500 mb-2">{{ $stats['total_participants'] }}</span>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Participantes</span>
        </div>
        <div class="dashboard-card p-8 flex flex-col items-center text-center">
            <span class="text-4xl font-black text-amber-500 mb-2">{{ $stats['total_judges'] }}</span>
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Jueces Pro</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="dashboard-card p-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-slate-800">Próximos Eventos</h2>
                    <a href="{{ route('admin.events.index') }}" class="text-sky-500 text-sm font-bold hover:underline">Ver Todos</a>
                </div>
                
                <div class="overflow-hidden border border-slate-100 rounded-2xl">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Evento</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 italic italic font-medium">
                            @foreach($recentEvents as $event)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-6 font-bold truncate max-w-[200px] uppercase">{{ $event->name }}</td>
                                    <td class="px-6 py-6 text-slate-500 text-sm italic italic">{{ $event->getFormattedDate() }}</td>
                                    <td class="px-6 py-6 text-xs">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-black rounded-full uppercase italic tracking-tighter">Activo</span>
                                    </td>
                                    <td class="px-6 py-6 font-bold text-sky-500 text-sm cursor-pointer hover:underline">
                                        <a href="{{ route('admin.events.edit', $event) }}">Gestionar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="dashboard-card p-10 bg-slate-900 border-none">
                <h3 class="text-xl font-bold text-white mb-6 uppercase tracking-widest italic italic">Acciones Rápidas</h3>
                <div class="space-y-4">
                    <a href="{{ route('admin.events.create') }}" class="block px-6 py-4 bg-white/10 hover:bg-sky-500 text-white rounded-2xl font-bold transition">Crear Nuevo Evento</a>
                    <a href="{{ route('admin.participants.create') }}" class="block px-6 py-4 bg-white/10 hover:bg-indigo-500 text-white rounded-2xl font-bold transition">Registrar Participante</a>
                    <a href="{{ route('admin.judges.index') }}" class="block px-6 py-4 bg-white/10 hover:bg-amber-500 text-white rounded-2xl font-bold transition">Gestionar Jueces</a>
                </div>
            </div>
        </div>
    </div>
@endsection
