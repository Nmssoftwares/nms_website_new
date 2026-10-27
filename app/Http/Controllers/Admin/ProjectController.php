<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        $projects = Project::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        unset($data['image']);

        if ($path = $this->replaceUpload($request, 'image', 'projects')) {
            $data['image_path'] = $path;
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project added.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validateData($request);
        unset($data['image']);

        if ($path = $this->replaceUpload($request, 'image', 'projects', $project->image_path)) {
            $data['image_path'] = $path;
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $this->deleteUpload($project->image_path);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project removed.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:600',
            'tech_stack' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:4096',
        ]);
    }
}