<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Healthcare Analytics Platform',
                'category' => 'Healthcare · Analytics',
                'description' => 'A comprehensive analytics platform for healthcare providers, featuring advanced data visualization, predictive analytics, and real-time monitoring. Enhances patient care and operational efficiency.',
                'tech_stack' => 'React, Node.js, Python, TensorFlow',
                'sort_order' => 1,
            ],
            [
                'title' => 'Medical Device Control System',
                'category' => 'Medical Devices',
                'description' => 'Machine-learning powered control system for medical devices with monitoring, predictive maintenance, and high-precision workflow automation.',
                'tech_stack' => 'Python, TensorFlow, C++, ROS',
                'sort_order' => 2,
            ],
            [
                'title' => 'Healthcare Management Suite',
                'category' => 'Enterprise Healthcare',
                'description' => 'Enterprise healthcare suite with AI-based decision support, patient management, operations monitoring and advanced procedure optimization.',
                'tech_stack' => 'Python, PyTorch, ROS, C++',
                'sort_order' => 3,
            ],
            [
                'title' => 'Medical Procedure Planning System',
                'category' => 'Surgical Planning',
                'description' => 'AI-assisted planning tool with real-time monitoring, surgery simulation and resource optimization.',
                'tech_stack' => 'Python, TensorFlow, ROS, CUDA',
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(['title' => $project['title']], $project);
        }
    }
}