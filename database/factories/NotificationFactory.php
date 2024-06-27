<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'message' => $this->faker->sentence,
            'is_read' => $this->faker->boolean,
            'announcement_id' => Announcement::factory(),
        ];
    }
}
