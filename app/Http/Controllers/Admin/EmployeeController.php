<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TeamCategory;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $categories = TeamCategory::withCount('employees')
            ->orderBy('sort_order')->orderBy('name')->get();

        $active = $request->query('category');

        $employees = Employee::with('category')
            ->when($active === 'none', fn ($q) => $q->whereNull('team_category_id'))
            ->when(is_numeric($active), fn ($q) => $q->where('team_category_id', (int) $active))
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.employees.index', [
            'employees'     => $employees,
            'categories'    => $categories,
            'active'        => $active,
            'total'         => Employee::count(),
            'uncategorized' => Employee::whereNull('team_category_id')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.employees.create', ['categories' => $this->categories()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        unset($data['photo']);

        if ($path = $this->replaceUpload($request, 'photo', 'employees')) {
            $data['photo_path'] = $path;
        }

        Employee::create($data);

        return redirect()->route('admin.employees.index')->with('status', 'Team member added.');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', [
            'employee'   => $employee,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validateData($request);
        unset($data['photo']);

        if ($path = $this->replaceUpload($request, 'photo', 'employees', $employee->photo_path)) {
            $data['photo_path'] = $path;
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('status', 'Team member updated.');
    }

    public function destroy(Employee $employee)
    {
        $this->deleteUpload($employee->photo_path);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('status', 'Team member removed.');
    }

    private function categories()
    {
        return TeamCategory::orderBy('sort_order')->orderBy('name')->get();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:120',
            'role'             => 'required|string|max:120',
            'team_category_id' => 'nullable|exists:team_categories,id',
            'bio'              => 'nullable|string|max:600',
            'linkedin_url'     => 'nullable|url|max:255',
            'x_url'            => 'nullable|url|max:255',
            'email'            => 'nullable|email|max:190',
            'sort_order'       => 'nullable|integer|min:0',
            'photo'            => 'nullable|image|max:4096',
        ]);
    }
}