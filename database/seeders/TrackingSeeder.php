<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Tracking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrackingSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            Tracking::factory()->create(
                [
                    'user_id' => User::inRandomOrder()->first()->id,
                    'announcement_id' => Announcement::inRandomOrder()->first()->id,
                    'created_at' => $this->randomDateTime(),
                ]
            );
        }
    }

    private function randomDateTime()
    {
        $start = Carbon::now()->subYear();
        $end = Carbon::now();

        return Carbon::createFromTimestamp(rand($start->timestamp, $end->timestamp));
    }
}
