<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Requests\StoreParticipantRequest;
use App\Services\ParticipantService;
use App\DTOs\ParticipantData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct(
        protected ParticipantService $participantService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Participant::with(['user', 'event'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantRequest $request): JsonResponse
    {
        $dto = ParticipantData::fromRequest($request->validated());
        $participant = $this->participantService->registerParticipant($dto);

        if ($request->hasFile('photo')) {
            $participant->addMediaFromRequest('photo')->toMediaCollection('photo');
            $participant->update(['photo_path' => $participant->getFirstMediaUrl('photo')]);
        }

        return response()->json($participant->load(['user', 'event']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant): JsonResponse
    {
        return response()->json($participant->load(['user', 'event']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string'],
            'status' => ['sometimes', 'string', 'in:activo,descalificado'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $participant->update($validated);

        if ($request->hasFile('photo')) {
            $participant->clearMediaCollection('photo');
            $participant->addMediaFromRequest('photo')->toMediaCollection('photo');
            $participant->update(['photo_path' => $participant->getFirstMediaUrl('photo')]);
        }

        return response()->json($participant->load(['user', 'event']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant): JsonResponse
    {
        $participant->user->delete(); // Cascades to participant
        return response()->json(null, 204);
    }
}
