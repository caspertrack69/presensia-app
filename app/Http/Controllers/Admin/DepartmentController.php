<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::with('manager')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $managers = User::whereIn('role', ['manager', 'admin'])
            ->orderBy('name')
            ->get();

        return view('admin.departments.create', compact('managers'));
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'Departemen baru berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        $managers = User::whereIn('role', ['manager', 'admin'])
            ->orderBy('name')
            ->get();

        return view('admin.departments.edit', compact('department', 'managers'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'Data departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus departemen yang masih memiliki anggota.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('status', 'Departemen berhasil dihapus.');
    }
}
