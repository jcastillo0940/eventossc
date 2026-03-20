<?php

namespace App\DTOs;

use DateTime;

readonly class EventDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public DateTime $date,
        public bool $is_timed,
        public bool $is_published = false,
        public ?array $settings = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? '',
            date: new DateTime($data['date']),
            is_timed: (bool) ($data['is_timed'] ?? false),
            is_published: (bool) ($data['is_published'] ?? false),
            settings: $data['settings'] ?? []
        );
    }
}
