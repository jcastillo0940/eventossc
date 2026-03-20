<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QRGeneratorService
{
    public function generateForParticipant(int $eventId, int $participantId): array
    {
        $hash = Str::random(16);
        $payload = "v1|{$eventId}|{$participantId}|{$hash}";
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($payload);

        $fileName = "qr/participant_{$participantId}.png";
        Storage::disk('public')->put($fileName, $qrCode);

        return [
            'payload' => $payload,
            'path' => $fileName,
            'url' => Storage::url($fileName)
        ];
    }
}
