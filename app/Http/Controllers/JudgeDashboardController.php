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
            $query->where('user_id', $user->id)->where('is_active', true);
        })->where('is_active', true)->get();

        return view('judge.dashboard', compact('events'));
    }

    public function evaluate(Event $event): View
    {
        $user = Auth::user();
        
        // Ensure the judge is assigned to this event and active
        if (!$event->judges()->where('user_id', $user->id)->where('is_active', true)->exists()) {
            abort(403, 'Usted no está asignado o no se encuentra activo para este evento.');
        }

        $categories = $event->evaluationCategories()
            ->where('is_active', true)
            ->with(['criteria' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();
        $participants = $event->participants()->where('is_active', true)->get();

        return view('judge.evaluate', compact('event', 'categories', 'participants'));
    }
}
