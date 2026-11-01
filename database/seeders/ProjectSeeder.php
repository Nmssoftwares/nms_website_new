<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            // ---- New projects (image taken from public/images, or downloaded if missing) ----
            [
                'title'       => 'Offnexa — Office Management System',
                'category'    => 'ERP · SaaS',
                'type'        => 'software',
                'description' => 'Offnexa brings HR, payroll, finance, inventory, visitors and helpdesk into one secure workspace. 24 connected modules (attendance, leave, payroll, recruitment, expense claims, invoicing, vendor payments, stock and assets, meeting rooms, CRM leads, BI reports) can be switched on per company. Role-based access for Super Admin, Company Admin and Employee, with the interface in English, Hindi and Gujarati.',
                'tech_stack'  => 'Laravel, MySQL, Role-based Access, Multi-language',
                'website_url' => 'https://offnexa.com',
                'sort_order'  => 1,
                'image_url'   => 'https://offnexa.com/assets/img/hero-office.png',
                'image_file'  => 'offnexa.png',
            ],
            [
                'title'       => 'OM Construction — Tile & Granite Contractor Website',
                'category'    => 'Business Website',
                'type'        => 'software',
                'description' => 'A lead-generating website for OM Construction, a Vadodara tile, kota stone, granite and epoxy flooring contractor serving clients since 2016. It includes service pages with quote requests, a filterable work gallery, a before/after comparison, client reviews, a flooring tips blog, FAQ and a WhatsApp enquiry form that sends leads straight to the owner.',
                'tech_stack'  => 'Laravel, Responsive Design, WhatsApp Integration, SEO',
                'website_url' => 'https://omconstructionworks.in',
                'sort_order'  => 2,
                'image_url'   => 'https://omconstructionworks.in/storage/seed/site/hero_bg.png',
                'image_file'  => 'omconstruction.png',
            ],

            // ---- Existing sample projects (no image) ----
            [
                'title' => 'Healthcare Analytics Platform',
                'category' => 'Healthcare · Analytics',
                'type' => 'software',
                'description' => 'A comprehensive analytics platform for healthcare providers, featuring advanced data visualization, predictive analytics, and real-time monitoring. Enhances patient care and operational efficiency.',
                'tech_stack' => 'React, Node.js, Python, TensorFlow',
                'sort_order' => 3,
            ],
            [
                'title' => 'Medical Device Control System',
                'category' => 'Medical Devices',
                'type' => 'hardware',
                'description' => 'Machine-learning powered control system for medical devices with monitoring, predictive maintenance, and high-precision workflow automation.',
                'tech_stack' => 'Python, TensorFlow, C++, ROS',
                'sort_order' => 4,
            ],
            [
                'title' => 'Healthcare Management Suite',
                'category' => 'Enterprise Healthcare',
                'type' => 'software',
                'description' => 'Enterprise healthcare suite with AI-based decision support, patient management, operations monitoring and advanced procedure optimization.',
                'tech_stack' => 'Python, PyTorch, ROS, C++',
                'sort_order' => 5,
            ],
            [
                'title' => 'Medical Procedure Planning System',
                'category' => 'Surgical Planning',
                'type' => 'software',
                'description' => 'AI-assisted planning tool with real-time monitoring, surgery simulation and resource optimization.',
                'tech_stack' => 'Python, TensorFlow, ROS, CUDA',
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $data) {
            // Add the image only for projects that have one
            if (isset($data['image_url'])) {
                $path = $this->storeImage($data['image_url'], $data['image_file']);
                if ($path) {
                    $data['image_path'] = $path;
                }
            }
            unset($data['image_url'], $data['image_file']);

            // Same title = update instead of creating a duplicate
            Project::updateOrCreate(['title' => $data['title']], $data);
        }
    }

    /**
     * 1) Use public/images/<file> if it exists.
     * 2) Otherwise download it from the website.
     * Returns the storage path (storage/app/public/projects/...) or null.
     */
    private function storeImage(string $url, string $file): ?string
    {
        $path  = 'projects/' . $file;
        $local = public_path('images/' . $file);

        // 1) Your own image in public/images wins
        if (File::exists($local)) {
            Storage::disk('public')->put($path, File::get($local));
            return $path;
        }

        // 2) Otherwise download it from the website
        try {
            $response = Http::timeout(30)->get($url);
            if ($response->successful() && $response->body() !== '') {
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Throwable $e) {
            $this->command?->warn("Could not download {$url}: " . $e->getMessage());
        }

        $this->command?->warn("No image saved for {$file}. Add it later from the admin panel.");
        return null;
    }
}