<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCategory;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use App\Models\Criterion;
use App\Services\ScoreService;
use App\DTOs\ScoreSubmissionDTO;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class DigitizerController extends Controller
{
    public function __construct(
        protected ScoreService $scoreService
    ) {}

    public function index(Request $request): View
    {
        $events = Event::where('is_active', true)->get();
        $preload = null;

        if ($request->filled('data')) {
            try {
                $preload = json_decode(base64_decode($request->query('data')), true);
            } catch (Exception $e) {}
        }

        return view('digitizer.index', compact('events', 'preload'));
    }

    public function getCriteria(EvaluationCategory $category): JsonResponse
    {
        return response()->json($category->criteria);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_id' => 'required|exists:evaluation_categories,id',
            'participant_id' => 'required|exists:participants,id',
            'judge_id' => 'required|exists:users,id',
            'scores' => 'required|array',
            'scores.*.criterion_id' => 'required|exists:criteria,id',
            'scores.*.score' => 'required|numeric|min:0|max:5'
        ]);

        try {
            // Transform scores array to [criterion_id => score] for DTO
            $scoresMap = [];
            foreach ($validated['scores'] as $item) {
                $scoresMap[$item['criterion_id']] = $item['score'];
            }
            
            $data = $validated;
            $data['scores'] = $scoresMap;

            $dto = ScoreSubmissionDTO::fromRequest($data);
            $this->scoreService->saveScores($dto);

            return response()->json(['message' => 'La papeleta fue registrada satisfactoriamente.', 'id' => uniqid('P-')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function checkExisting(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required',
            'category_id' => 'required',
            'participant_id' => 'required',
            'judge_id' => 'required',
        ]);

        $scores = \App\Models\Score::where([
            'event_id' => $validated['event_id'],
            'category_id' => $validated['category_id'],
            'participant_id' => $validated['participant_id'],
            'judge_id' => $validated['judge_id'],
        ])->get();

        return response()->json([
            'exists' => $scores->isNotEmpty(),
            'scores' => $scores->pluck('score', 'criterion_id')
        ]);
    }

    public function getCategoryDetails(Request $request): JsonResponse
    {
        $eventId = $request->query('event_id');
        if (!$eventId) return response()->json([]);

        return response()->json([
            'categories' => EvaluationCategory::where('event_id', $eventId)->get(),
            'participants' => Participant::where('event_id', $eventId)->get(),
            'judges' => User::role('Juez')->get()
        ]);
    }
}
