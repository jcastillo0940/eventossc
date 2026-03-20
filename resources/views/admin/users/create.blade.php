@extends('layouts.admin')

@section('title', 'Nuevo Staff')
@section('header_title', 'Registro de Personal')

@section('content')
    <div class="mb-12">
        <h1 class="text-3xl font-black text-slate-900 uppercase italic italic">Registrar <span class="text-sky-500">Staff</span></h1>
        <p class="text-slate-500 font-medium font-bold italic italic">Cree cuentas para administradores o digitadores de eventos.</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-10 max-w-2xl mx-auto italic italic font-medium">
        @csrf
        
        <div class="dashboard-card p-10 space-y-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Nombre Completo</label>
                <input type="text" name="name" required placeholder="Ej: Juan Pérez" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Email de Acceso</label>
                <input type="email" name="email" required placeholder="juan@eventos.com" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Contraseña</label>
                <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Rol en el Sistema</label>
                <select name="role" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 transition-all italic italic font-medium">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between p-6 rounded-2xl bg-slate-50/50 border border-slate-100">
                <span class="text-sm font-black text-slate-800 uppercase italic italic">Usuario Activo</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-6">
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 font-black uppercase text-xs tracking-widest hover:text-slate-600 transition italic italic font-medium">Cancelar</a>
            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition shadow-xl shadow-slate-400/20 italic italic font-medium">
                Guardar Personal
            </button>
        </div>
    </form>
@endsection
