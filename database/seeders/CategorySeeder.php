<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General'],
            ['name' => 'Important'],
            ['name' => 'Event'],
            ['name' => 'Birthday'],
            ['name' => 'Policy'],
            ['name' => 'HR'],
            ['name' => 'Training'],
        ];

        foreach ($categories as $categoryName) {
            Category::factory()->create($categoryName);
        }
    }
}
