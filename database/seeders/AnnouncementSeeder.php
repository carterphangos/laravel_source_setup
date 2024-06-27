<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $usersWithRoleZero = User::where('role', 0)->get();

        $announcements = [
            [
                'name' => 'Welcome new member to our company',
                'content' => '<h1>Welcome new member to our company</h1><p>Welcome aboard! We are excited to have you join our team. Your skills and experience will make a valuable contribution to our success.</p><p>Feel free to reach out to your team members and managers if you have any questions or need assistance.</p>',
            ],
            [
                'name' => 'Change Human resource',
                'content' => '<h1>Change Human resource</h1><p>We are making updates to our HR policies and procedures to better serve our employees. Please review the changes and contact HR if you have any questions.</p><p>Thank you for your cooperation and understanding during this transition.</p>',
            ],
            [
                'name' => 'Change office location',
                'content' => '<h1>Change office location</h1><p>Important notice: Our office location is changing. Please take note of the new address and plan your commute accordingly.</p><p>Reach out to the facilities team if you need assistance or have any questions about the move.</p>',
            ],
        ];

        foreach ($announcements as $announcementData) {
            $randomUser = $usersWithRoleZero->random();
            $announcement = Announcement::factory()->create([
                'title' => $announcementData['name'],
                'content' => $announcementData['content'],
                'author_id' => $randomUser->id,
            ]);

            $announcement->categories()->attach(
                Category::inRandomOrder()->limit(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
