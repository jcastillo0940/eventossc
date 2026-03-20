<?php

namespace App\Services;

use App\DTOs\ParticipantData;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParticipantService
{
    public function __construct(
        protected QRGeneratorService $qrService
    ) {}

    public function registerParticipant(ParticipantData $data): Participant
    {
        return DB::transaction(function () use ($data) {
            // 1. Create User
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
            ]);

            // 2. Assign Role
            $user->assignRole('Participante');

            // 3. Create Participant
            $participant = Participant::create([
                'user_id' => $user->id,
                'event_id' => $data->event_id,
                'name' => $data->name,
                'category' => $data->category,
                'status' => $data->status,
            ]);

            // 4. Generate QR
            $qrInfo = $this->qrService->generateForParticipant($data->event_id, $participant->id);
            
            $participant->update([
                'qr_payload' => $qrInfo['payload'],
                'photo_path' => $qrInfo['url'] // Initially OR temporary, photo will be uploaded later
            ]);

            return $participant;
        });
    }
}
