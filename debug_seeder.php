<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\EvaluationCategory;
use App\Models\Criterion;
use App\Models\Participant;
use App\Models\User;
use App\Models\Score;
use Illuminate\Support\Str;

try {
    $event = Event::create([
        'name' => 'Debug Rock',
        'slug' => 'debug-rock-'.rand(1,999),
        'date' => now(),
        'is_active' => true,
    ]);

    $cat = EvaluationCategory::create(['event_id' => $event->id, 'name' => 'Test']);
    $crit = Criterion::create(['category_id' => $cat->id, 'name' => 'Test', 'max_score' => 10, 'weight' => 1]);
    $user = User::create(['name' => 'P1', 'email' => rand().'@t.com', 'password' => 'X']);
    $p = Participant::create(['user_id' => $user->id, 'event_id' => $event->id, 'name' => 'P1', 'category' => 'X', 'social_points' => 10]);
    $judge = User::create(['name' => 'J1', 'email' => rand().'@j.com', 'password' => 'X']);

    Score::create([
        'event_id' => $event->id,
        'participant_id' => $p->id,
        'judge_id' => $judge->id,
        'category_id' => $cat->id,
        'criterion_id' => $crit->id,
        'score' => 10
    ]);
    echo "SUCCESS";
} catch (\Exception $e) {
    echo $e;
}
