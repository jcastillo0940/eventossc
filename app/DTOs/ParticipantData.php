<?php

namespace App\DTOs;

readonly class ParticipantData
{
    public function __construct(
        public int $event_id,
        public string $name,
        public string $email,
        public string $password,
        public string $category,
        public ?string $status = 'activo'
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            event_id: (int) $data['event_id'],
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            category: $data['category'],
            status: $data['status'] ?? 'activo'
        );
    }
}
