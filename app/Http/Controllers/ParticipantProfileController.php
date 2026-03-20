<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ParticipantProfileController extends Controller
{
    public function show(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('Participante')) {
            return response()->json(['error' => 'No eres un participante.'], 403);
        }

        $participant = $user->participant->load(['event.settings', 'event.brands']);

        return response()->json([
            'participant' => $participant,
            'qr_url' => $participant->photo_path // Since I stored URL there, or I can use QRGeneratorService logic
        ]);
    }
}
