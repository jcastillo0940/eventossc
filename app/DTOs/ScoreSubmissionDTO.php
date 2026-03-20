<?php

namespace App\DTOs;

readonly class ScoreSubmissionDTO
{
    public function __construct(
        public int $event_id,
        public int $category_id,
        public int $participant_id,
        public int $judge_id,
        public array $criteria // [criterion_id => score]
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            event_id: (int) $data['event_id'],
            category_id: (int) $data['category_id'],
            participant_id: (int) $data['participant_id'],
            judge_id: (int) $data['judge_id'],
            criteria: $data['scores']
        );
    }
}
