<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

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
