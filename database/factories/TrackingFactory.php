<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'announcement_id' => Announcement::factory(),
        ];
    }
}
