<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::orderBy('date', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'date_display_mode' => 'required|string|in:full,month_year,tba',
            'description' => 'nullable|string',
            'is_active' => 'nullable|string',
            'is_published' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $event = Event::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'date' => $validated['date'],
            'date_display_mode' => $validated['date_display_mode'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active'),
            'is_published' => $request->has('is_published'),
        ]);

        // Settings
        $settings = [
            'show_leaderboard_to_participants' => $request->has('show_leaderboard_to_participants') ? 'true' : 'false',
            'enable_public_vote' => $request->has('enable_public_vote') ? 'true' : 'false',
            'enable_social_points' => $request->has('enable_social_points') ? 'true' : 'false',
            'event_schedule' => $request->input('event_schedule') ?? '',
            'about_subtitle' => $request->input('about_subtitle') ?? 'Sobre el Evento',
            'about_title' => $request->input('about_title') ?? 'Una competencia familiar y emocionante',
        ];

        foreach ($settings as $key => $value) {
            $event->settings()->create(['key' => $key, 'value' => $value ?? '']);
        }

        if ($request->hasFile('logo')) {
            $event->addMediaFromRequest('logo')->toMediaCollection('logo');
            $event->update(['logo_path' => $event->getFirstMediaUrl('logo')]);
        }

        if ($request->hasFile('banner')) {
            $event->addMediaFromRequest('banner')->toMediaCollection('banner');
            $event->update(['banner_path' => $event->getFirstMediaUrl('banner')]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Evento creado con éxito.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
            'date_display_mode' => 'required|string|in:full,month_year,tba',
            'description' => 'nullable|string',
        ]);

        $event->update([
            'name'              => $validated['name'],
            'slug'              => Str::of($validated['name'])->slug(),
            'date'              => $validated['date'],
            'date_display_mode' => $validated['date_display_mode'],
            'description'       => $validated['description'],
            'is_active'         => $request->has('is_active'),
            'is_published'      => $request->has('is_published'),
        ]);

        // Settings 
        $settings = [
            'show_leaderboard_to_participants' => $request->has('show_leaderboard_to_participants') ? 'true' : 'false',
            'enable_public_vote' => $request->has('enable_public_vote') ? 'true' : 'false',
            'enable_social_points' => $request->has('enable_social_points') ? 'true' : 'false',
            'event_schedule' => $request->input('event_schedule') ?? '',
            'about_subtitle' => $request->input('about_subtitle') ?? 'Sobre el Evento',
            'about_title' => $request->input('about_title') ?? 'Una competencia familiar y emocionante',
        ];

        foreach ($settings as $key => $value) {
            $event->settings()->updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        if ($request->hasFile('logo')) {
            $event->clearMediaCollection('logo');
            $event->addMediaFromRequest('logo')->toMediaCollection('logo');
            $event->update(['logo_path' => $event->getFirstMediaUrl('logo')]);
        }

        if ($request->hasFile('banner')) {
            $event->clearMediaCollection('banner');
            $event->addMediaFromRequest('banner')->toMediaCollection('banner');
            $event->update(['banner_path' => $event->getFirstMediaUrl('banner')]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Evento actualizado.');
    }

    /**
     * Toggle is_active status via AJAX.
     */
    public function toggle(Event $event): JsonResponse
    {
        $event->update(['is_active' => !$event->is_active]);
        return response()->json(['is_active' => $event->is_active]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        // Integrity check: if it has scores, don't allow delete, maybe just deactivate
        if ($event->scores()->exists()) {
            return back()->with('error', 'No se puede eliminar un evento con calificaciones registradas. Use el interruptor para desactivarlo.');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Evento eliminado.');
    }
}
