<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Event;
use App\Models\EvaluationCategory;
use App\Models\Criterion;
use App\Models\Participant;
use App\Models\User;
use App\Models\Brand;
use App\Models\Score;
use Illuminate\Support\Str;

class Phase4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::firstOrCreate(
            ['slug' => 'rock-fest-2026'],
            [
                'name' => 'Rock Fest 2026',
                'date' => now()->addDays(10),
                'description' => 'El evento más grande del año.',
                'is_active' => true,
            ]
        );

        // Event Settings
        $event->settings()->createMany([
            ['key' => 'enable_public_vote', 'value' => 'true'],
            ['key' => 'enable_social_points', 'value' => 'true'],
            ['key' => 'public_vote_weight', 'value' => '0.5'],
        ]);

        // Brands (Sponsors)
        $event->brands()->createMany([
            ['name' => 'Coca-Cola', 'logo_path' => 'public/logos/coke.png', 'order' => 1],
            ['name' => 'Pepsi', 'logo_path' => 'public/logos/pepsi.png', 'order' => 2],
            ['name' => 'Red Bull', 'logo_path' => 'public/logos/redbull.png', 'order' => 3],
        ]);

        // Categories & Criteria
        $cat = EvaluationCategory::create([
            'event_id' => $event->id,
            'name' => 'Presentación General'
        ]);

        $criterion = Criterion::create([
            'category_id' => $cat->id,
            'name' => 'Energía en vivo',
            'max_score' => 10,
            'weight' => 1.0
        ]);

        // Participants
        $bands = ['Los Rockers', 'Metal Gods', 'Punk Stars'];
        foreach ($bands as $name) {
            $user = User::create([
                'name' => $name,
                'email' => Str::slug($name).'@example.com',
                'password' => bcrypt('password')
            ]);
            $user->assignRole('Participante');

            $p = Participant::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'name' => $name,
                'category' => 'Banda',
                'social_points' => rand(5, 20),
                'status' => 'activo'
            ]);
        }

        // Judge User
        $judgeUser = User::create([
            'name' => 'Judge One',
            'email' => 'judge@example.com',
            'password' => bcrypt('password')
        ]);
        $judgeUser->assignRole('Juez');

        // Assign some scores
        foreach (Participant::where('event_id', $event->id)->get() as $p) {
            Score::create([
                'event_id' => $event->id,
                'participant_id' => $p->id,
                'judge_id' => $judgeUser->id,
                'criterion_id' => $criterion->id,
                'category_id' => $cat->id,
                'score' => rand(5, 10)
            ]);
        }
    }
}
