<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\TeamCategory;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'project-manager' => ['name' => 'Project Manager',                 'is_featured' => true,  'sort_order' => 1],
            'back-end-team'   => ['name' => 'Our Back End Team',               'is_featured' => false, 'sort_order' => 2],
            'application-development-team' => ['name' => 'Our Application Development Team', 'is_featured' => false, 'sort_order' => 3],
        ];

        $cat = [];
        foreach ($categories as $slug => $c) {
            $cat[$slug] = TeamCategory::updateOrCreate(
                ['slug' => $slug],
                ['name' => $c['name'], 'is_featured' => $c['is_featured'], 'is_visible' => true, 'sort_order' => $c['sort_order']]
            );
        }

        $members = [
            // Project Manager
            [
                'name'  => 'Ghanshyam Prajapati',
                'role'  => 'Project Manager',
                'cat'   => 'project-manager',
                'photo' => 'ghanshyam-prajapati.jpg',
                'order' => 1,
                'bio'   => 'Ghanshyam leads project delivery at NMS Softwares, turning client ideas into well-planned, on-time software. He coordinates the back end and application teams, keeps requirements clear, manages timelines and risks, and makes sure every release meets the quality our clients expect. His focus is simple: clear communication, a dependable process, and a team that enjoys building great products.',
            ],

            // Back End Team
            ['name' => 'Sonal Kumari',        'role' => 'Back End Developer',   'cat' => 'back-end-team',   'photo' => 'sonal-kumari.jpg',        'order' => 1],
            ['name' => 'Mahek Bloch',         'role' => 'Back End Developer',   'cat' => 'back-end-team',   'photo' => 'mahek-bloch.jpg',         'order' => 2],
            ['name' => 'Vaishnavi Chalke',    'role' => 'Back End Developer',   'cat' => 'back-end-team',   'photo' => 'vaishnavi-chalke.jpg',    'order' => 3],

            // Application Development Team
            ['name' => 'Yashvi Patel',        'role' => 'Application Developer', 'cat' => 'application-development-team', 'photo' => 'yashvi-patel.jpg', 'order' => 1],
            ['name' => 'Suhani Tomar',        'role' => 'Application Developer', 'cat' => 'application-development-team', 'photo' => 'suhani-tomar.jpg', 'order' => 2],
        ];

        foreach ($members as $m) {
            $data = [
                'role'             => $m['role'],
                'team_category_id' => $cat[$m['cat']]->id,
                'photo_path'       => $m['photo'] ? 'images/team/' . $m['photo'] : null,
                'sort_order'       => $m['order'],
                'is_locked'        => true,
            ];

            // Only set a bio when one is written here, so bios edited in Admin are not wiped.
            if (! empty($m['bio'])) {
                $data['bio'] = $m['bio'];
            }

            Employee::updateOrCreate(['name' => $m['name']], $data);
        }
    }
}