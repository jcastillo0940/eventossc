<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Services\RankingService;

class ParticipantDashboardController extends Controller
{
    public function __construct(
        protected RankingService $rankingService
    ) {}

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Find the specific participation of the user
        /** @var Participant|null $participantProfile */
        $participantProfile = Participant::with('event')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        $event = null;
        $ranking = null;
        $showLeaderboard = false;

        if ($participantProfile !== null) {
            /** @var Event|null $event */
            $event = $participantProfile->event;

            if ($event !== null) {
                // Feature Flag: show_leaderboard_to_participants
                $showLeaderboard = $event->getSetting('show_leaderboard_to_participants') === 'true';

                if ($showLeaderboard) {
                    $ranking = $this->rankingService->getEventRanking($event);
                }
            }
        }

        return view('participant.dashboard', compact('participantProfile', 'ranking', 'showLeaderboard'));
    }
}
