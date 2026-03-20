<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EvaluationCategory;
use App\Models\Participant;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminAuditController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::all();
        $categories = EvaluationCategory::where('event_id', $request->event_id)->get();
        $participants = Participant::where('event_id', $request->event_id)->get();

        $query = Score::with(['participant', 'criterion.category', 'judge', 'event']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('criterion', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('participant_id')) {
            $query->where('participant_id', $request->participant_id);
        }

        $scores = $query->latest()->paginate(25);

        return view('admin.audit.index', compact('events', 'categories', 'participants', 'scores'));
    }

    /**
     * Emergency edit of a score.
     */
    public function update(Request $request, Score $score): RedirectResponse
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'reason' => 'required|string', // Audit log
        ]);

        $oldValue = $score->score;
        $score->update(['score' => $validated['score']]);

        // Audit Log
        Log::channel('audit')->info('Puntaje Editado por Admin', [
            'admin_id' => Auth::id(),
            'score_id' => $score->id,
            'old_value' => $oldValue,
            'new_value' => $validated['score'],
            'reason' => $validated['reason'],
            'participant' => $score->participant->name,
            'criterion' => $score->criterion->name,
        ]);

        return back()->with('success', 'Puntaje corregido y logueado en auditoría.');
    }
}
