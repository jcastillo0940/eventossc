@extends('layouts.app-dashboard')

@section('title', 'Evaluando: ' . $event->name)
@section('header_title', 'Evaluación de Competidores')

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2 uppercase italic italic">{{ $event->name }}</h1>
            <p class="text-slate-500 font-medium font-bold">Seleccione un participante para calificar sus criterios.</p>
        </div>
        <a href="{{ route('judge.dashboard') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic">Volver al Panel</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Lista de Participantes -->
        <div class="lg:col-span-1 space-y-4">
            <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-6">Competidores en Escena</h2>
            @foreach($participants as $p)
                <button type="button" 
                        onclick="selectParticipant({{ $p->id }}, '{{ $p->name }}', '{{ $p->category }}')"
                        class="w-full text-left dashboard-card p-6 flex items-center gap-4 hover:border-indigo-500 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-xs text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                        {{ substr($p->name, 0, 2) }}
                    </div>
                    <div>
                        <div class="text-sm font-black text-slate-800 uppercase italic italic">{{ $p->name }}</div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $p->category }}</div>
                    </div>
                </button>
            @endforeach
        </div>

        <!-- Formulario de Calificación (Oculto hasta elegir participante) -->
        <div class="lg:col-span-2">
            <div id="selection-placeholder" class="dashboard-card p-20 text-center flex flex-col items-center gap-6 border-dashed border-2">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                     <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <p class="text-slate-400 italic italic font-medium">Por favor, seleccione un participante de la lista para comenzar la evaluación.</p>
            </div>

            <div id="evaluation-form" class="hidden space-y-8">
                <div class="dashboard-card p-8 bg-slate-900 border-none">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-black uppercase text-sky-400 tracking-widest block mb-1">Evaluando a</span>
                            <h3 id="current-participant-name" class="text-2xl font-black text-white uppercase italic italic">NOMBRE DEL COMPETIDOR</h3>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest block mb-1">Categoría</span>
                            <span id="current-participant-category" class="text-white font-bold italic italic uppercase">CATEGORIA</span>
                        </div>
                    </div>
                </div>

                <form action="#" method="POST" class="space-y-10">
                    @csrf
                    @foreach($categories as $category)
                        <div class="dashboard-card p-10 space-y-8 italic italic font-medium">
                            <h4 class="text-xs font-black uppercase text-slate-400 tracking-[0.2em] border-b border-slate-50 pb-4">{{ $category->name }}</h4>
                            <div class="space-y-10">
                                @foreach($category->criteria as $criterion)
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label class="text-sm font-black text-slate-800 uppercase italic italic">{{ $criterion->name }}</label>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Máx {{ $criterion->max_score }}</span>
                                        </div>
                                        <div class="grid grid-cols-10 gap-2">
                                            @for($i = 1; $i <= $criterion->max_score; $i++)
                                                <label class="relative group cursor-pointer">
                                                    <input type="radio" name="scores[{{ $criterion->id }}]" value="{{ $i }}" class="sr-only peer" required>
                                                    <div class="h-12 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-black text-xs transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 group-hover:bg-slate-100">
                                                        {{ $i }}
                                                    </div>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-12 py-5 rounded-[2rem] font-black text-sm uppercase tracking-widest transition shadow-xl shadow-emerald-500/20 italic italic">
                            Enviar Calificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectParticipant(id, name, category) {
            document.getElementById('selection-placeholder').classList.add('hidden');
            document.getElementById('evaluation-form').classList.remove('hidden');
            document.getElementById('current-participant-name').innerText = name;
            document.getElementById('current-participant-category').innerText = category;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
@endsection
