<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Notification::factory()->create(
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
