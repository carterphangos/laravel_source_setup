<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory(100)->create();
        for ($i = 0; $i < 300; $i++) {
            Comment::factory()->create([
                'user_id' => User::inRandomOrder()->first()->id,
                'post_id' => Post::inRandomOrder()->first()->id,
            ]);
        }
    }
}
