@extends('layouts.admin')

@section('title', 'Categorías de Evaluación')
@section('header_title', 'Rúbrica de ' . $event->name)

@section('content')
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Categorías de <span class="text-sky-500">Evaluación</span></h1>
            <p class="text-slate-500 font-medium font-bold italic italic">Defina las áreas que serán calificadas en este evento.</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition italic italic font-medium underline">Volver a Eventos</a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 font-bold italic italic">
             {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- New Category Form -->
        <div class="lg:col-span-1">
            <div class="dashboard-card p-10 italic italic font-medium">
                <h3 class="text-lg font-black text-slate-800 uppercase italic italic mb-8">Nueva Categoría</h3>
                <form action="{{ route('admin.events.categories.store', $event) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre de la Categoría</label>
                        <input type="text" name="name" required placeholder="Ej: Presentación, Sabor, etc." class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium">
                    </div>
                    <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-sky-400/20 italic italic font-medium">
                        Añadir Categoría
                    </button>
                </form>
            </div>
        </div>

        <!-- Categories List -->
        <div class="lg:col-span-2 space-y-6">
            @foreach($categories as $category)
                <div class="dashboard-card p-8 flex items-center justify-between group hover:border-sky-500 transition-all italic italic font-medium">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 font-black group-hover:bg-sky-50 group-hover:text-sky-500 transition-colors italic italic">
                             {{ $loop->iteration }}
                        </div>
                        <div>
                            <h4 class="text-lg font-black text-slate-900 uppercase italic italic">{{ $category->name }}</h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $category->criteria_count }} Criterios configurados</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.events.categories.criteria.index', [$event, $category]) }}" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition shadow-lg shadow-slate-900/10 italic italic">
                            Ver Rúbrica
                        </a>
                        
                        <div x-data="{ active: {{ $category->is_active ? 'true' : 'false' }} }">
                            <button @click="fetch('{{ route('admin.events.categories.toggle', [$event, $category]) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => active = d.is_active)"
                                    class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                                    :class="active ? 'bg-emerald-500' : 'bg-slate-200'">
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                      :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
