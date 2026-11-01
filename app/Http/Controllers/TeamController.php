<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TeamCategory;

class TeamController extends Controller
{
    public function index()
    {
        $categories = TeamCategory::where('is_visible', true)
            ->with('employees')
            ->orderBy('sort_order')->orderBy('name')
            ->get()
            ->filter(fn ($c) => $c->employees->isNotEmpty())
            ->values();

        $others = Employee::whereNull('team_category_id')
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('team.index', compact('categories', 'others'));
    }
}