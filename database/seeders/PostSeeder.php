<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How we validate ML models for clinical systems',
                'slug' => 'validating-ml-models-for-clinical-systems',
                'excerpt' => 'Notes on testing, edge cases and safety margins before deployment.',
                'body_html' => '<p>Replace this with your real article content. You can write full HTML here, '
                    . 'or swap this field for a Markdown pipeline if you prefer writing in Markdown.</p>'
                    . '<p>Cover your validation pipeline, edge-case testing strategy, and the safety '
                    . 'margins you build in before anything ships to a clinical environment.</p>',
                'author' => 'NMS Softwares',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Designing control software for real-time robotics',
                'slug' => 'designing-control-software-real-time-robotics',
                'excerpt' => 'Lessons from building precision workflow automation with ROS.',
                'body_html' => '<p>Replace this with your real article content covering your ROS-based '
                    . 'control architecture, timing guarantees, and lessons learned from production deployments.</p>',
                'author' => 'NMS Softwares',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Scaling analytics platforms for healthcare data',
                'slug' => 'scaling-analytics-platforms-healthcare-data',
                'excerpt' => 'Architecture choices behind our latest monitoring platform.',
                'body_html' => '<p>Replace this with your real article content on data pipeline architecture, '
                    . 'storage choices, and how you scale analytics workloads for healthcare clients.</p>',
                'author' => 'NMS Softwares',
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
