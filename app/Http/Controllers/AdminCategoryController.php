<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EvaluationCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCategoryController extends Controller
{
    public function index(Event $event): View
    {
        $categories = $event->evaluationCategories()->withCount('criteria')->get();
        return view('admin.events.categories.index', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $event->evaluationCategories()->create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.events.categories.index', $event)->with('success', 'Categoría creada.');
    }

    public function toggle(Event $event, EvaluationCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return response()->json(['is_active' => $category->is_active]);
    }

    public function destroy(Event $event, EvaluationCategory $category): RedirectResponse
    {
        if ($category->criteria()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría con criterios. Desactívela en su lugar.');
        }

        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}
