<?php

namespace App\Services;

use App\DTOs\ScoreSubmissionDTO;
use App\Models\Score;
use Illuminate\Support\Facades\DB;
use Exception;

class ScoreService
{
    /**
     * @param ScoreSubmissionDTO $data
     * @return void
     * @throws Exception
     */
    public function saveScores(ScoreSubmissionDTO $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Verificar si ya existe calificación para esta categoría/plato
            $exists = Score::where([
                'participant_id' => $data->participant_id,
                'judge_id' => $data->judge_id,
                'category_id' => $data->category_id
            ])->exists();

            if ($exists) {
                throw new Exception("Este juez ya calificó esta categoría/plato para este participante.");
            }

            // 2. Guardar los puntajes por criterio
            foreach ($data->criteria as $criterionId => $value) {
                Score::create([
                    'event_id' => $data->event_id,
                    'category_id' => $data->category_id,
                    'participant_id' => $data->participant_id,
                    'judge_id' => $data->judge_id,
                    'criterion_id' => $criterionId,
                    'score' => $value
                ]);
            }
        });
    }
}
