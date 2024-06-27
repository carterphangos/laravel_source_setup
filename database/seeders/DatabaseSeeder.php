<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            AnnouncementSeeder::class,
            TrackingSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
