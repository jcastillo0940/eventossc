<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Services\EventService;
use App\DTOs\EventDTO;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Event::with(['settings', 'brands'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $dto = EventDTO::fromRequest($request->validated());
        $event = $this->eventService->createEvent($dto);

        if ($request->hasFile('logo')) {
            $event->addMediaFromRequest('logo')->toMediaCollection('logo');
            $event->update(['logo_path' => $event->getFirstMediaUrl('logo')]);
        }

        if ($request->hasFile('banner')) {
            $event->addMediaFromRequest('banner')->toMediaCollection('banner');
            $event->update(['banner_path' => $event->getFirstMediaUrl('banner')]);
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $event->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return response()->json($event->load(['settings', 'brands']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): JsonResponse
    {
        return response()->json($event->load(['settings', 'brands']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $dto = EventDTO::fromRequest($request->validated());
        $event = $this->eventService->updateEvent($event, $dto);

        if ($request->hasFile('logo')) {
            $event->clearMediaCollection('logo');
            $event->addMediaFromRequest('logo')->toMediaCollection('logo');
            $event->update(['logo_path' => $event->getFirstMediaUrl('logo')]);
        }

        if ($request->hasFile('banner')) {
            $event->clearMediaCollection('banner');
            $event->addMediaFromRequest('banner')->toMediaCollection('banner');
            $event->update(['banner_path' => $event->getFirstMediaUrl('banner')]);
        }

        if ($request->hasFile('gallery')) {
            // Usually we might want to keep or clear depending on UX, but let's add new ones
            foreach ($request->file('gallery') as $image) {
                $event->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return response()->json($event->load(['settings', 'brands']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json(null, 204);
    }
}
