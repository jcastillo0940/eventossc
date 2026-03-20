<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminJudgeController extends Controller
{
    public function index(): View
    {
        $judges = User::role('Juez')->with('judgeEvents')->get();
        return view('admin.judges.index', compact('judges'));
    }

    public function create(): View
    {
        $events = Event::all();
        return view('admin.judges.create', compact('events'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'bio' => 'nullable|string',
            'specialty' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'events' => 'nullable|array',
            'events.*' => 'exists:events,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'bio' => $validated['bio'],
            'is_active' => $request->has('is_active'),
        ]);

        $user->assignRole('Juez');

        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->toMediaCollection('profile');
            $user->update(['photo_path' => $user->getFirstMediaUrl('profile')]);
        }

        if ($request->filled('events')) {
            foreach ($validated['events'] as $eventId) {
                $user->judgeEvents()->attach($eventId, ['specialty' => $validated['specialty'] ?? 'General']);
            }
        }

        return redirect()->route('admin.judges.index')->with('success', 'Juez registrado y vinculado.');
    }

    public function edit(User $judge): View
    {
        $events = Event::all();
        $assignedEvents = $judge->judgeEvents->pluck('id')->toArray();
        return view('admin.judges.edit', compact('judge', 'events', 'assignedEvents'));
    }

    public function update(Request $request, User $judge): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $judge->id,
            'password' => 'nullable|min:6',
            'bio' => 'nullable|string',
            'specialty' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'events' => 'nullable|array',
            'events.*' => 'exists:events,id',
        ]);

        $judge->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'bio' => $validated['bio'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->filled('password')) {
            $judge->update(['password' => Hash::make($validated['password'])]);
        }

        if ($request->hasFile('photo')) {
            $judge->clearMediaCollection('profile');
            $judge->addMediaFromRequest('photo')->toMediaCollection('profile');
            $judge->update(['photo_path' => $judge->getFirstMediaUrl('profile')]);
        }

        // Sync pivot (event_judge)
        // With current structure, User is linked to many Events. But wait, sync is better.
        // If pivot table event_judges has id, user_id, event_id, specialty.
        // detach and re-attach is safer if using specialty column. 
        $judge->judgeEvents()->syncWithPivotValues($validated['events'] ?? [], ['specialty' => $validated['specialty'] ?? 'General']);

        return redirect()->route('admin.judges.index')->with('success', 'Información del Juez actualizada.');
    }

    public function toggle(User $judge)
    {
        $judge->update(['is_active' => !$judge->is_active]);
        return response()->json(['is_active' => $judge->is_active]);
    }
}
