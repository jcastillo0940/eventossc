<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ParticipantWebController extends Controller
{
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasRole('Participante')) {
            abort(403, 'No eres un participante.');
        }

        $participant = $user->participant->load(['event.settings', 'event.brands']);

        return view('participants.profile', compact('participant'));
    }
}
