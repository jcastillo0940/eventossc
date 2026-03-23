<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Services\RankingService;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __construct(
        protected RankingService $rankingService
    ) {}

    public function show(string $slug): View
    {
        $event = Event::with([
                'participants' => function($query) {
                    $query->where('is_active', true)->with(['scores', 'publicVotes']);
                },
                'judges' => function($query) {
                    $query->where('is_active', true)->with('user');
                },
                'brands' => function($query) {
                    $query->where('is_active', true);
                }
            ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $ranking = $this->rankingService?->getEventRanking($event);

        return view('landing.event', compact('event', 'ranking'));
    }
}
