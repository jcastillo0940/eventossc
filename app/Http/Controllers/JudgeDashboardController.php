<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class JudgeDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Fetch events associated with this judge
        $events = Event::whereHas('judges', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('is_active', true)->get();

        return view('judge.dashboard', compact('events'));
    }

    public function evaluate(Event $event): View
    {
        $user = Auth::user();
        
        // Ensure the judge is assigned to this event
        if (!$event->judges()->where('user_id', $user->id)->exists()) {
            abort(403, 'Usted no está asignado a este evento.');
        }

        $categories = $event->evaluationCategories()->with('criteria')->get();
        $participants = $event->participants()->where('is_active', true)->get();

        return view('judge.evaluate', compact('event', 'categories', 'participants'));
    }
}
