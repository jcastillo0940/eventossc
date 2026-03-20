@extends('layouts.admin')

@section('title', 'Gestión de Staff')
@section('header_title', 'Administradores y Digitadores')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Gestión de <span class="text-sky-500">Staff</span></h1>
            <p class="text-slate-500 font-medium font-bold">Administre el acceso de SuperAdmins y Digitadores.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-3 bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-slate-900/10">
            Nuevo Personal
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl mb-8 font-bold flex items-center gap-3">
             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
             {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Nombre / Email</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Rol</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Estado</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 italic italic font-medium">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-slate-900 uppercase italic italic">{{ $user->name }}</div>
                                <div class="text-[10px] text-slate-400 font-bold tracking-widest">{{ $user->email }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                    {{ $user->roles->pluck('name')->first() }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div x-data="{ active: {{ $user->is_active ? 'true' : 'false' }}, loading: false }">
                                    <button @click="loading = true; fetch('{{ route('admin.users.toggle', $user) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
                                            .then(r => r.json()).then(d => {active = d.is_active; loading = false})"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors ease-in-out focus:outline-none"
                                            :class="active ? 'bg-sky-500' : 'bg-slate-200'">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                              :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3 font-bold text-xs uppercase italic italic tracking-tighter">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-sky-500 hover:underline">Editar</a>
                                    <span class="text-slate-100">|</span>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Desactivar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-500 transition-colors">Desactivar</button>
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
