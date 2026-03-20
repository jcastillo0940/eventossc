<?php

namespace App\Services;

use App\DTOs\EventDTO;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function createEvent(EventDTO $dto): Event
    {
        return DB::transaction(function () use ($dto) {
            $slug = Str::slug($dto->name);
            $originalSlug = $slug;
            $count = 1;

            while (Event::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $event = Event::create([
                'name' => $dto->name,
                'slug' => $slug,
                'description' => $dto->description,
                'date' => $dto->date,
                'is_timed' => $dto->is_timed,
                'is_published' => $dto->is_published,
                'is_active' => true,
            ]);

            if (!empty($dto->settings)) {
                foreach ($dto->settings as $key => $value) {
                    $event->settings()->create([
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }

            return $event;
        });
    }

    public function updateEvent(Event $event, EventDTO $dto): Event
    {
        return DB::transaction(function () use ($event, $dto) {
            $event->update([
                'name' => $dto->name,
                'description' => $dto->description,
                'date' => $dto->date,
                'is_timed' => $dto->is_timed,
                'is_published' => $dto->is_published,
            ]);

            if (!empty($dto->settings)) {
                foreach ($dto->settings as $key => $value) {
                    $event->settings()->updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }

            return $event;
        });
    }
}
