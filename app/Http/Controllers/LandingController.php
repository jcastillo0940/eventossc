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
                'participants.scores', 
                'participants.publicVotes',
                'judges.user', 
                'brands'
            ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $ranking = $this->rankingService?->getEventRanking($event);

        return view('landing.event', compact('event', 'ranking'));
    }
}
