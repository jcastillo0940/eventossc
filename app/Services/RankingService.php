<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Score;
use App\Models\PublicVote;
use Illuminate\Support\Collection;

class RankingService
{
    /**
     * @param Event $event
     * @return Collection
     */
    public function getEventRanking(Event $event): Collection
    {
        $enableSocial = $event->getSetting('enable_social_points', 'false') === 'true';
        $enablePublic = $event->getSetting('enable_public_vote', 'false') === 'true';
        $publicWeight = (float) $event->getSetting('public_vote_weight', 0);

        return $event->participants()->with(['scores', 'publicVotes'])->get()
            ->map(function ($participant) use ($enableSocial, $enablePublic, $publicWeight) {
                // 1. Puntaje de Jueces
                $judgeScore = $participant->scores->sum('score');

                // 2. Puntos Sociales
                $socialScore = $enableSocial ? (float) $participant->social_points : 0;

                // 3. Puntos Voto Público
                $publicVotesCount = $participant->publicVotes->count();
                $publicScore = $enablePublic ? ($publicVotesCount * $publicWeight) : 0;

                return [
                    'participant' => $participant,
                    'judge_score' => $judgeScore,
                    'social_score' => $socialScore,
                    'public_votes' => $publicVotesCount,
                    'public_score' => $publicScore,
                    'total_score' => $judgeScore + $socialScore + $publicScore
                ];
            })
            ->sortByDesc('total_score')
            ->values();
    }
}
