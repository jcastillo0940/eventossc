<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class AdminParticipantController extends Controller
{
    public function index(): View
    {
        $participants = Participant::with('event', 'user')->get();
        return view('admin.participants.index', compact('participants'));
    }

    public function create(): View
    {
        $events = Event::all();
        return view('admin.participants.create', compact('events'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'category' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Create User for login
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('password'), // Static or generated
        ]);
        $user->assignRole('Participante');

        $participant = Participant::create([
            'user_id' => $user->id,
            'event_id' => $validated['event_id'],
            'name' => $validated['name'],
            'category' => $validated['category'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('photo')) {
            $participant->addMediaFromRequest('photo')->toMediaCollection('photo');
            $participant->update(['photo_path' => $participant->getFirstMediaUrl('photo')]);
        }

        return redirect()->route('admin.participants.index')->with('success', 'Participante registrado.');
    }

    public function edit(Participant $participant): View
    {
        $events = Event::all();
        return view('admin.participants.edit', compact('participant', 'events'));
    }

    public function update(Request $request, Participant $participant): RedirectResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name'     => 'required|string|max:255',
            'category' => 'required|string',
            'photo'    => 'nullable|image|max:2048',
        ]);

        $participant->update([
            'event_id'  => $validated['event_id'],
            'name'      => $validated['name'],
            'category'  => $validated['category'],
            'is_active' => $request->has('is_active'),
        ]);

        // Also sync user name
        $participant->user()->update(['name' => $validated['name']]);

        if ($request->hasFile('photo')) {
            $participant->addMediaFromRequest('photo')->toMediaCollection('photo');
            $participant->update(['photo_path' => $participant->getFirstMediaUrl('photo')]);
        }

        return redirect()->route('admin.participants.index')->with('success', 'Participante actualizado.');
    }

    public function toggle(Participant $participant)
    {
        $participant->update(['is_active' => !$participant->is_active]);
        return response()->json(['is_active' => $participant->is_active]);
    }
}
