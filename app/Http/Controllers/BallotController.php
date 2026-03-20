<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Participant;
use App\Models\EvaluationCategory;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;

class BallotController extends Controller
{
    /**
     * @param Event $event
     * @return Response
     */
    public function print(Event $event): Response
    {
        // Get only judges assigned to this event
        $eventJudges = $event->judges()->with('user')->where('is_active', true)->get();
        $participants = $event->participants()->where('is_active', true)->get();
        $categories = $event->evaluationCategories()->where('is_active', true)->get();

        $ballots = [];

        foreach ($participants as $participant) {
            foreach ($categories as $category) {
                foreach ($eventJudges as $eventJudge) {
                    $judge = $eventJudge->user;
                    
                    $params = base64_encode(json_encode([
                        'event_id' => $event->id,
                        'category_id' => $category->id,
                        'participant_id' => $participant->id,
                        'judge_id' => $judge->id
                    ]));

                    $url = route('digitizer.index') . '?data=' . $params;

                    $qrCode = base64_encode(QrCode::format('svg')->size(150)->generate($url));

                    $ballots[] = [
                        'event_name' => $event->name,
                        'participant_name' => $participant->name,
                        'category_name' => $category->name,
                        'judge_name' => $judge->name,
                        'qr_code' => $qrCode,
                        'criteria' => $category->criteria
                    ];
                }
            }
        }

        $pdf = Pdf::loadView('ballots.pdf', compact('ballots', 'event'));
        return $pdf->download("papeletas-{$event->slug}.pdf");
    }
}
