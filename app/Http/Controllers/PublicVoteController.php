<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PublicVote;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class PublicVoteController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'participant_id' => 'required|exists:participants,id',
            'fingerprint' => 'required|string'
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // 1. Check Feature Flag
        if ($event->getSetting('enable_public_vote', 'false') !== 'true') {
            return response()->json(['error' => 'La votación pública no está activa para este evento.'], 403);
        }

        // 2. Prevent duplicate votes by fingerprint
        $exists = PublicVote::where('event_id', $event->id)
            ->where('fingerprint', $validated['fingerprint'])
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Ya has votado en este evento.'], 422);
        }

        try {
            PublicVote::create($validated);
            return response()->json(['message' => '¡Voto registrado con éxito!']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al registrar el voto.'], 500);
        }
    }
}
