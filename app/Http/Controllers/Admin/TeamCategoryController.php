<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamCategoryController extends Controller
{
    public function index()
    {
        $categories = TeamCategory::withCount('employees')
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.team-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        TeamCategory::create($data);

        return redirect()->route('admin.team-categories.index')->with('status', 'Category added.');
    }

    public function edit(TeamCategory $team_category)
    {
        return view('admin.team-categories.edit', ['category' => $team_category]);
    }

    public function update(Request $request, TeamCategory $team_category)
    {
        $team_category->update($this->validated($request));

        return redirect()->route('admin.team-categories.index')->with('status', 'Category updated.');
    }

    public function destroy(TeamCategory $team_category)
    {
        $team_category->delete();

        return redirect()->route('admin.team-categories.index')
            ->with('status', 'Category deleted. Its members are now uncategorized.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'       => 'required|string|max:80',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['sort_order']  = $data['sort_order'] ?? 0;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_visible']  = $request->boolean('is_visible');

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 2;

        while (TeamCategory::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}