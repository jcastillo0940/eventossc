<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function store(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $brand = $event->brands()->create([
            'name' => $validated['name'],
            'order' => $validated['order'] ?? 0,
        ]);

        if ($request->hasFile('logo')) {
            $brand->addMediaFromRequest('logo')->toMediaCollection('logo');
            $brand->update(['logo_path' => $brand->getFirstMediaUrl('logo')]);
        }

        return response()->json($brand, 201);
    }
}
