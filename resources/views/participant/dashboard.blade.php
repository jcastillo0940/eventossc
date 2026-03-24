@extends('layouts.app-dashboard')

@section('title', 'Mi Portal de Participante')
@section('role_name', 'Participante')
@section('header_title', 'Espacio del Competidor')

@section('content')
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2 uppercase italic italic">Mi <span class="text-emerald-500 tracking-tighter">Portal</span></h1>
        <p class="text-slate-500 font-medium font-bold">Bienvenido a tu área personal del evento.</p>
    </div>

    @if(!$participantProfile)
        <div class="dashboard-card p-12 text-center text-slate-500 italic font-medium italic font-bold">No se encontró un perfil de participante asociado a tu cuenta.</div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- QR Card -->
            <div class="dashboard-card p-10 bg-slate-900 border-none flex flex-col items-center text-center space-y-8">
                <span class="text-[10px] font-black text-white/50 uppercase tracking-[0.3em] mb-4">Credencial Digital</span>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-2xl">
                    {!! QrCode::size(200)->generate($participantProfile->qr_payload ?? $participantProfile->id) !!}
                </div>
                <div>
                   <h3 class="text-2xl font-black text-white uppercase italic italic tracking-tighter mb-1">{{ $participantProfile->name }}</h3>
                   <span class="text-emerald-400 font-bold uppercase tracking-widest text-[10px]">{{ $participantProfile->category }}</span>
                </div>
                <div class="w-full h-[1px] bg-white/10 my-4"></div>
                <button class="w-full flex items-center justify-center gap-3 bg-white/10 hover:bg-white/20 text-white h-14 rounded-2xl font-bold transition">
                    Descargar Ticket PDF
                </button>
            </div>

            <!-- Main Portal Section -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Info Section -->
                <div class="dashboard-card p-10">
                    <h2 class="text-2xl font-bold text-slate-800 mb-8 italic italic">Detalles del Evento</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic italic font-medium">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Evento Principal</span>
                            <span class="text-lg font-bold text-slate-800 truncate uppercase">{{ $participantProfile->event->name }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Fecha de Presentación</span>
                            <span class="text-lg font-bold text-slate-800">{{ $participantProfile->event->getFullFormattedDate() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard Section (Optional) -->
                @if($showLeaderboard)
                    <div class="dashboard-card p-10 overflow-hidden">
                        <div class="flex items-center justify-between mb-8">
                           <h2 class="text-2xl font-bold text-slate-800 italic italic">Live Leaderboard</h2>
                           <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black uppercase italic italic">Live</span>
                        </div>
                        
                        <div class="border rounded-2xl overflow-hidden">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4">Pos</th>
                                        <th class="px-6 py-4">Participante</th>
                                        <th class="px-6 py-4 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-bold italic italic">
                                    @foreach($ranking as $index => $row)
                                        <tr class="hover:bg-slate-50 transition-colors {{ $row['participant']->id == $participantProfile->id ? 'bg-sky-50 animate-pulse' : '' }}">
                                            <td class="px-6 py-6 font-black text-slate-400">#{{ $index + 1 }}</td>
                                            <td class="px-6 py-6 truncate">{{ $row['participant']->name }}</td>
                                            <td class="px-6 py-6 text-right text-sky-500">{{ number_format($row['total_score'], 1) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="glass p-12 rounded-[2.5rem] bg-indigo-50 border-indigo-100 flex flex-col items-center text-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-indigo-500 shadow-xl shadow-indigo-500/10">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-indigo-900 italic italic">Leaderboard en proceso</h3>
                        <p class="text-indigo-700/60 font-medium italic italic">Los resultados están siendo tabulados por el jurado. ¡El ganador será anunciado pronto!</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection
