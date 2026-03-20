<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCategory;
use App\Models\Criterion;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminCriterionController extends Controller
{
    public function index(Event $event, EvaluationCategory $category): View
    {
        $criteria = $category->criteria()->get();
        return view('admin.events.categories.criteria.index', compact('event', 'category', 'criteria'));
    }

    public function store(Request $request, Event $event, EvaluationCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_score' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
        ]);

        $category->criteria()->create([
            'name' => $validated['name'],
            'max_score' => $validated['max_score'],
            'weight' => $validated['weight'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.events.categories.criteria.index', [$event, $category])->with('success', 'Criterio añadido.');
    }

    public function toggle(Event $event, EvaluationCategory $category, Criterion $criterion)
    {
        $criterion->update(['is_active' => !$criterion->is_active]);
        return response()->json(['is_active' => $criterion->is_active]);
    }

    public function destroy(Event $event, EvaluationCategory $category, Criterion $criterion): RedirectResponse
    {
        // Integrity check: if it has scores, don't allow delete
        if (\App\Models\Score::where('criterion_id', $criterion->id)->exists()) {
             return back()->with('error', 'No se puede eliminar un criterio con evaluaciones registradas.');
        }

        $criterion->delete();
        return back()->with('success', 'Criterio eliminado.');
    }
}
