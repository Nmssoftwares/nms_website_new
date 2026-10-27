<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        $employees = Employee::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
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
        return view('admin.employees.edit', compact('employee'));
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

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'required|string|max:120',
            'bio' => 'nullable|string|max:600',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:190',
            'sort_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:4096',
        ]);
    }
}