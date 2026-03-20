<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(): View
    {
        // Only SuperAdmins and Digitadores (Juries have their own CRUD)
        $users = User::role(['SuperAdmin', 'Digitador'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('name', ['SuperAdmin', 'Digitador'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->has('is_active'),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user): View
    {
        $roles = Role::whereIn('name', ['SuperAdmin', 'Digitador'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['is_active' => $user->is_active]);
    }

    public function destroy(User $user): RedirectResponse
    {
        // Check if user has performed actions (e.g. scored as Judge) - though here they are Admin/Digitador
        // If they are digitadores and have scores assigned via digitizer, maybe just deactivate
        $user->update(['is_active' => false]);
        return back()->with('success', 'Usuario desactivado para preservar historial.');
    }
}
