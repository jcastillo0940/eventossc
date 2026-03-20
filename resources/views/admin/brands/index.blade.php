@extends('layouts.admin')

@section('title', 'Patrocinadores')
@section('header_title', 'Aliados y Marcas')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Directorio de <span class="text-indigo-500">Aliados</span></h1>
            <p class="text-slate-500 font-medium font-bold italic italic">Gestione las marcas y patrocinadores vinculados a los eventos.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center justify-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-indigo-400/20 italic italic font-medium">
            Registrar Marca
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 font-bold italic italic font-medium">
             {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 italic italic font-medium">
        @forelse($brands as $brand)
            <div class="dashboard-card p-10 flex flex-col items-center text-center group hover:border-indigo-500 transition-all relative">
                <!-- Toggle -->
                <div x-data="{ active: {{ $brand->is_active ? 'true' : 'false' }} }" class="absolute top-6 right-6">
                    <button @click="fetch('{{ route('admin.brands.toggle', $brand) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r => r.json()).then(d => active = d.is_active)"
                            class="relative inline-flex h-4 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                            :class="active ? 'bg-indigo-500' : 'bg-slate-200'">
                        <span class="pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                              :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>

                <div class="w-20 h-20 rounded-[2rem] bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden mb-6 p-4">
                    @if($brand->logo_path)
                        <img src="{{ $brand->logo_path }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-slate-200 font-black text-[9px] italic italic">LOGO</span>
                    @endif
                </div>

                <h3 class="text-lg font-black text-slate-900 uppercase italic italic tracking-tight mb-1">{{ $brand->name }}</h3>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Orden: {{ $brand->order }}</span>
                
                <div class="flex flex-wrap justify-center gap-1 mb-6">
                    @php $count = $brand->events->count(); @endphp
                    <span class="text-[9px] font-black {{ $count > 0 ? 'bg-sky-100 text-sky-600' : 'bg-slate-100 text-slate-400' }} px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $count }} {{ Str::plural('Evento', $count) }}
                    </span>
                </div>

                <div class="w-full h-[1px] bg-slate-100 mb-6"></div>
                <div class="flex gap-4 w-full">
                    <a href="{{ route('admin.brands.edit', $brand) }}" class="flex-grow py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl font-bold text-[10px] uppercase transition italic italic">Editar</a>
                </div>
            </div>
        @empty
            <div class="lg:col-span-4 dashboard-card p-20 text-center text-slate-400 font-bold italic italic">No hay patrocinadores registrados.</div>
        @endforelse
    </div>
@endsection
