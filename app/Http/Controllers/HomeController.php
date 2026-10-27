<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Post;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $projects    = Project::orderBy('sort_order')->orderBy('title')->get();
        $employees   = Employee::orderBy('sort_order')->orderBy('name')->get();
        $latestPosts = Post::published()
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home', compact('projects', 'employees', 'latestPosts'));
    }
}