<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::with(['manager', 'employees'])->orderBy('name')->paginate(15);
        return view('hr.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', 1)->get(); // For parent department
        $employees = \App\Models\Employee::where('status', 'Active')->get(); // For manager
        
        return view('hr.departments.create', compact('departments', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:departments',
            'name' => 'required|max:100',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        \App\Models\Department::create($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::with(['parent', 'manager', 'employees', 'children'])
            ->findOrFail($id);
        
        return view('hr.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        $departments = Department::where('is_active', 1)
            ->where('id', '!=', $id)
            ->get(); // For parent department (exclude self)
        $employees = \App\Models\Employee::where('status', 'Active')->get();
        
        return view('hr.departments.edit', compact('department', 'departments', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|unique:departments,code,' . $id,
            'name' => 'required|max:100',
            'parent_id' => 'nullable|exists:departments,id',
            'manager_id' => 'nullable|exists:employees,id',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        // Prevent circular hierarchy
        if ($request->parent_id && $this->wouldCreateCircularReference($id, $request->parent_id)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['parent_id' => 'Cannot set parent department - would create circular reference.']);
        }

        $department->update($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Check if setting parent would create circular reference
     */
    private function wouldCreateCircularReference($departmentId, $parentId)
    {
        if ($departmentId == $parentId) {
            return true;
        }

        $parent = Department::find($parentId);
        while ($parent) {
            if ($parent->id == $departmentId) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        
        // Check if department has employees
        if ($department->employees()->count() > 0) {
            return redirect()->route('hr.departments.index')
                ->with('error', 'Cannot delete department with active employees. Please reassign employees first.');
        }
        
        // Check if department has sub-departments
        if ($department->children()->count() > 0) {
            return redirect()->route('hr.departments.index')
                ->with('error', 'Cannot delete department with sub-departments. Please delete or reassign sub-departments first.');
        }
        
        $department->delete();
        
        return redirect()->route('hr.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
