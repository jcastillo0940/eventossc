<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminBrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::with('events')->orderBy('order')->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        $events = Event::all();
        return view('admin.brands.create', compact('events'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id',
            'name' => 'required|string|max:255',
            'order' => 'integer',
            'logo' => 'nullable|image|max:2048',
        ]);

        $brand = Brand::create([
            'name' => $validated['name'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        $brand->events()->sync($validated['event_ids']);

        if ($request->hasFile('logo')) {
            $brand->addMediaFromRequest('logo')->toMediaCollection('logo');
            $brand->update(['logo_path' => $brand->getFirstMediaUrl('logo')]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Patrocinador registrado.');
    }

    public function edit(Brand $brand): View
    {
        $events = Event::all();
        return view('admin.brands.edit', compact('brand', 'events'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id',
            'name'     => 'required|string|max:255',
            'order'    => 'integer',
            'logo'     => 'nullable|image|max:2048',
        ]);

        $brand->update([
            'name'      => $validated['name'],
            'order'     => $validated['order'] ?? $brand->order,
            'is_active' => $request->has('is_active'),
        ]);

        $brand->events()->sync($validated['event_ids']);

        if ($request->hasFile('logo')) {
            $brand->clearMediaCollection('logo');
            $brand->addMediaFromRequest('logo')->toMediaCollection('logo');
            $brand->update(['logo_path' => $brand->getFirstMediaUrl('logo')]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Patrocinador actualizado.');
    }

    public function toggle(Brand $brand)
    {
        $brand->update(['is_active' => !$brand->is_active]);
        return response()->json(['is_active' => $brand->is_active]);
    }
}
